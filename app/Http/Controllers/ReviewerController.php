<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Research_title;
use App\Models\researcher_files;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReviewerController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Only show initial-review statuses; revision-related papers go to Re-Evaluation
        $revisionStatuses = ['Waiting for Revision', 'Revision Submitted', 'Reviewing Revisions'];

        $titles = Research_title::where(function ($q) use ($userId) {
            // Modern Pivot logic
            $q->whereHas('reviewers', function ($query) use ($userId) {
                $query->where('users.id', $userId)
                    ->where('title_reviewer_assignments.status', 'Pending');
            })
                // Legacy JSON logic handling
                ->orWhere(function ($query) use ($userId) {
                    $query->whereDoesntHave('reviewers')
                        ->where(function ($q2) use ($userId) {
                            $q2->whereJsonContains('assigned_reviewers', (string) $userId)
                                ->orWhereJsonContains('assigned_reviewers', $userId);
                        })
                        ->where('Status', '!=', 'Reviewed');
                });
        })
            ->whereNotIn('Status', $revisionStatuses)
            ->latest()
            ->get();

        return view('reviewer.dashboard', compact('titles'));
    }

    public function reEvaluation()
    {
        $userId = Auth::id();

        // Show all revision-related statuses so the reviewer can track the full cycle
        $revisionStatuses = ['Waiting for Revision', 'Revision Submitted', 'Reviewing Revisions'];

        $titles = Research_title::where(function ($q) use ($userId) {
            $q->whereHas('reviewers', function ($query) use ($userId) {
                $query->where('users.id', $userId)
                    ->where('title_reviewer_assignments.status', 'Pending');
            })
                ->orWhere(function ($query) use ($userId) {
                    $query->whereDoesntHave('reviewers')
                        ->where(function ($q2) use ($userId) {
                            $q2->whereJsonContains('assigned_reviewers', (string) $userId)
                                ->orWhereJsonContains('assigned_reviewers', $userId);
                        })
                        ->where('Status', '!=', 'Reviewed');
                });
        })
            ->whereIn('Status', $revisionStatuses)
            ->latest()
            ->get();

        $pageTitle = 'Re-Evaluation';
        $pageDescription = 'Review protocols that have been revised by the researcher and returned for re-evaluation.';

        return view('reviewer.dashboard', compact('titles', 'pageTitle', 'pageDescription'));
    }

    public function viewFiles($id)
    {
        $researchTitle = Research_title::with(['researcher.user', 'files', 'adminFiles'])->findOrFail($id);

        // Automatically transition status when reviewer opens the files for the first time
        if ($researchTitle->Status === 'Reviewer Assigned') {
            $researchTitle->Status = 'Under Review';
            $researchTitle->save();
        }

        // Automatically transition status when reviewer opens files from Re-Evaluation
        if ($researchTitle->Status === 'Revision Submitted') {
            $researchTitle->Status = 'Reviewing Revisions';
            $researchTitle->save();
        }
        $requirementsMap = \App\Models\DocumentRequirement::all()->keyBy('name')->toArray();
        $backUrl = url()->previous(route('reviewer.dashboard'));

        try {
            $myFileRemarks = \App\Models\ReviewerFileRemark::whereIn('file_id', function ($query) use ($id) {
                // researcher_files has a direct foreign key to research_title
                $query->select('id')
                    ->from('researcher_files')
                    ->where('research_title_id', $id);
            })
                ->where('reviewer_id', Auth::id())
                ->get()
                ->keyBy('file_id');
        } catch (\Exception $e) {
            \Log::error('Error loading remarks: ' . $e->getMessage());
            $myFileRemarks = collect();
        }

        return view('reviewer.view_files', compact('researchTitle', 'backUrl', 'requirementsMap', 'myFileRemarks'));
    }

    public function serveFile($id)
    {
        $file = researcher_files::findOrFail($id);

        // Normalize path: remove 'storage/' prefix if present
        $path = str_replace('storage/', '', $file->filepath);

        // 1. Check Storage (Public Disk)
        if (Storage::disk('public')->exists($path)) {
            return response()->file(storage_path('app/public/' . $path));
        }

        // 2. Check Public Directory (Direct Access)
        $publicPath = public_path($file->filepath);
        if (file_exists($publicPath)) {
            return response()->file($publicPath);
        }

        // 3. Check Storage Path directly (Absolute)
        $storagePath = storage_path('app/public/' . $path);
        if (file_exists($storagePath)) {
            return response()->file($storagePath);
        }

        return abort(404, 'File not found.');
    }

    public function uploadFile(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|string',
            'file' => 'required|file|max:20480'
        ]);

        $file = $request->file('file');

        $originalExt = $file->getClientOriginalExtension();
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $modifiedName = $originalName . '_reviewer.' . $originalExt;

        // Ensure consistent path usage with Admin logic
        $path = $file->storeAs('uploads/research_files', time() . '_' . $modifiedName, 'public_uploads');

        researcher_files::create([
            'research_title_id' => $id,
            'filename' => $modifiedName,
            'filepath' => 'uploads/research_files/' . basename($path),
            'filetype' => $originalExt,
            'uploaded_by' => Auth::id(),
            'category' => 'Reviewer Uploads - ' . $request->input('category'),
            'revision_number' => 0
        ]);

        return back()->with('success', 'Evaluation Document Uploaded Successfully');
    }

    public function completeReview(Request $request, $id)
    {
        $submission = Research_title::findOrFail($id);

        // Determine if this is a re-evaluation (revision-related status)
        $isReEvaluation = in_array($submission->Status, ['Waiting for Revision', 'Revision Submitted', 'Reviewing Revisions']);

        // Prevent completing if no reviewer uploads exist
        $hasUploads = $submission->adminFiles()
            ->where('uploaded_by', Auth::id())
            ->where('category', 'like', 'Reviewer Uploads%')
            ->exists();

        if (!$hasUploads) {
            return back()->withErrors(['error' => 'You must upload at least one evaluation document before completing the review.']);
        }

        // Attach the suggested review type to the latest evaluation document uploaded by this reviewer
        $myUploads = $submission->adminFiles()
            ->where('uploaded_by', Auth::id())
            ->where('category', 'like', 'Reviewer Uploads%')
            ->latest()
            ->get();

        $latestUpload = $myUploads->first();

        if ($latestUpload && $request->has('suggested_review_type')) {
            $latestUpload->suggested_review_type = $request->input('suggested_review_type');
            $latestUpload->save();
        }

        // Save per-file remarks submitted from the modal (applies to researcher's files)
        $fileRemarks = $request->input('file_remarks', []);
        foreach ($fileRemarks as $fileId => $remark) {
            if (!empty(trim($remark))) {
                \App\Models\ReviewerFileRemark::updateOrCreate(
                    [
                        'reviewer_id' => Auth::id(),
                        'file_id' => $fileId
                    ],
                    [
                        'remarks' => trim($remark),
                        'research_title_id' => $id
                    ]
                );
            }
        }


        // Save Review Decision & Remarks (Re-Evaluation only)
        if ($request->has('review_decision')) {
            $submission->reviewer_decision = $request->input('review_decision');

            // Build structured deliberation message
            $deliberationNotes = "";
            if ($request->filled('scientific_soundness') || $request->filled('ethical_issues') || $request->filled('icf_issues') || $request->filled('summary_of_issues')) {
                $deliberationNotes = "=== DELIBERATION NOTES ===\n";
                $deliberationNotes .= "Scientific Soundness: " . $request->input('scientific_soundness', 'N/A') . "\n\n";
                $deliberationNotes .= "Ethical Issues: " . $request->input('ethical_issues', 'N/A') . "\n\n";
                $deliberationNotes .= "ICF Issues: " . $request->input('icf_issues', 'N/A') . "\n\n";
                $deliberationNotes .= "Summary of Issues & Resolutions: " . $request->input('summary_of_issues', 'N/A') . "\n\n";
            }

            $msg = $deliberationNotes . "=== FINAL DECISION ===\nReview Decision: " . $request->input('review_decision') . "\nRemarks: " . $request->input('remarks', 'None');
            \App\Models\SubmissionFeedback::create([
                'research_title_id' => $submission->id,
                'user_id' => Auth::id(),
                'type' => 'reviewer_decision',
                'message' => $msg
            ]);

            \App\Models\RevisionLog::create([
                'research_title_id' => $submission->id,
                'user_id' => Auth::id(),
                'message' => $msg
            ]);
        } elseif ($request->filled('suggested_review_type')) {
            // Initial review
            $message = "Suggested Review Type: " . $request->input('suggested_review_type');

            \App\Models\SubmissionFeedback::create([
                'research_title_id' => $submission->id,
                'user_id' => Auth::id(),
                'type' => 'reviewer_decision',
                'message' => $message
            ]);
        }

        $submission->Status = 'Reviewed';
        $submission->save();

        // Redirect based on context
        if ($isReEvaluation) {
            return redirect()->route('reviewer.reevaluation')->with('success', 'Re-evaluation completed successfully!');
        }

        return redirect()->route('reviewer.dashboard')->with('success', 'Protocol review marked as complete!');
    }

    public function saveFileRemark(Request $request, $fileId)
    {
        $request->validate(['remarks' => 'nullable|string|max:2000']);

        $remark = trim($request->input('remarks', ''));

        // Look up the research_title_id explicitly from the request, fallback to db lookup
        $titleId = $request->input('research_title_id');
        if (!$titleId) {
            $file = \App\Models\Researcher_files::find($fileId);
            $titleId = $file ? $file->research_title_id : null;

            // If still null, try finding it via the pivot table
            if (!$titleId) {
                $pivot = \DB::table('research_title_files')->where('researcher_file_id', $fileId)->first();
                $titleId = $pivot ? $pivot->research_title_id : null;
            }
        }

        if (!$titleId) {
            return response()->json(['success' => false, 'message' => 'Missing research_title_id.'], 400);
        }

        if ($remark === '') {
            // Delete existing remark if cleared
            \App\Models\ReviewerFileRemark::where('reviewer_id', Auth::id())
                ->where('file_id', $fileId)
                ->delete();
        } else {
            \App\Models\ReviewerFileRemark::updateOrCreate(
                ['reviewer_id' => Auth::id(), 'file_id' => $fileId],
                ['remarks' => $remark, 'research_title_id' => $titleId]
            );
        }

        return response()->json(['success' => true, 'message' => 'Remark saved.']);
    }

    public function reviewedTitles()
    {
        $userId = Auth::id();

        $titles = Research_title::where(function ($q) use ($userId) {
            $q->whereHas('reviewers', function ($query) use ($userId) {
                $query->where('users.id', $userId)
                    ->where('title_reviewer_assignments.status', 'Completed');
            })
                ->orWhere(function ($query) use ($userId) {
                    $query->whereDoesntHave('reviewers')
                        ->where(function ($q2) use ($userId) {
                            $q2->whereJsonContains('assigned_reviewers', (string) $userId)
                                ->orWhereJsonContains('assigned_reviewers', $userId);
                        })
                        ->where('Status', 'Reviewed');
                });
        })
            ->latest()
            ->get();

        return view('reviewer.reviewed_titles', compact('titles'));
    }
}
