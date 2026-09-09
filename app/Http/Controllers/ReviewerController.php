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
            ->with(['researcher.user'])
            ->latest()
            ->get();

        return view('reviewer.dashboard', compact('titles'));
    }

    public function reEvaluation()
    {
        $userId = Auth::id();

        // Show all revision-related statuses so the reviewer can track the full revision cycle
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
            ->with(['researcher.user'])
            ->latest()
            ->get();

        $pageTitle = 'Re-Evaluation';
        $pageDescription = 'Review protocols that have been revised by the researcher and returned for re-evaluation.';

        return view('reviewer.dashboard', compact('titles', 'pageTitle', 'pageDescription'));
    }

    /**
     * Authorize that the authenticated reviewer is assigned to the given research title,
     * or possesses administrator privileges.
     */
    protected function authorizeReviewerAssignment(Research_title $title): void
    {
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        if (in_array($user->role ?? '', ['admin', 'superadmin'])) {
            return;
        }

        $userId = $user->id;

        // 1. Check modern pivot assignment
        if ($title->reviewers()->where('users.id', $userId)->exists()) {
            return;
        }

        // 2. Check legacy JSON column assigned_reviewers
        $assigned = $title->assigned_reviewers;
        if (is_array($assigned) && in_array((string) $userId, array_map('strval', $assigned))) {
            return;
        } elseif (is_string($assigned)) {
            $decoded = json_decode($assigned, true);
            if (is_array($decoded) && in_array((string) $userId, array_map('strval', $decoded))) {
                return;
            }
        }

        abort(403, 'Unauthorized. You are not assigned to evaluate this research protocol.');
    }

    public function viewFiles($id)
    {
        $researchTitle = Research_title::with([
            'researcher.user',
            'files',
            'adminFiles.uploader',
            'titleLogs.user',
            'revisionLogs.user'
        ])->findOrFail($id);
        $this->authorizeReviewerAssignment($researchTitle);

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
            // Aggregate all file IDs belonging to this protocol (both researcher files via pivot and admin files)
            $allFileIds = $researchTitle->files->pluck('id')->merge($researchTitle->adminFiles->pluck('id'))->unique();

            $myFileRemarks = \App\Models\ReviewerFileRemark::where('reviewer_id', Auth::id())
                ->where(function ($q) use ($id, $allFileIds) {
                    $q->where('research_title_id', $id)
                        ->orWhereIn('file_id', $allFileIds);
                })
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

        // Polymorphic relation resolution: check effective_research_title (pivot) or direct foreign key
        $researchTitle = $file->effective_research_title;
        if (!$researchTitle && $file->research_title_id) {
            $researchTitle = Research_title::find($file->research_title_id);
        }

        if (!$researchTitle) {
            abort(404, 'Associated research protocol not found.');
        }

        $this->authorizeReviewerAssignment($researchTitle);

        $path = ltrim(str_replace('storage/', '', $file->filepath), '/');

        $respondWithFile = function (string $fullPath) use ($file) {
            $mimeType = \Illuminate\Support\Facades\File::mimeType($fullPath) ?: 'application/octet-stream';
            $disposition = request()->boolean('download') ? 'attachment' : 'inline';
            return response()->file($fullPath, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => "{$disposition}; filename=\"{$file->filename}\"",
            ]);
        };

        // 1. Check Storage (Public Disk)
        if (Storage::disk('public')->exists($path)) {
            return $respondWithFile(storage_path('app/public/' . $path));
        }

        // 2. Check public_uploads disk (root is public_path())
        if (Storage::disk('public_uploads')->exists($path)) {
            return $respondWithFile(public_path($path));
        }

        // 3. Check Public Directory (Direct Access)
        $publicPath = public_path($file->filepath);
        if (file_exists($publicPath)) {
            return $respondWithFile($publicPath);
        }

        // 4. Check Storage Path directly (Absolute fallback)
        $storagePath = storage_path('app/public/' . $path);
        if (file_exists($storagePath)) {
            return $respondWithFile($storagePath);
        }

        // 5. Institutional fallback view if physical binary missing from disk
        return response(
            '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <title>Document Unavailable</title>
                <script src="https://cdn.tailwindcss.com"></script>
            </head>
            <body class="bg-slate-50 flex items-center justify-center min-h-screen p-4 font-sans text-slate-700 antialiased">
                <div class="max-w-sm w-full bg-white rounded-2xl border border-slate-200/80 p-6 text-center shadow-xs">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center mx-auto mb-3 text-slate-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-xs font-semibold text-slate-900 mb-1">Document Unavailable on Disk</h3>
                    <p class="text-[11px] text-slate-500 mb-3 leading-relaxed break-all font-mono">' . htmlspecialchars($file->filename) . '</p>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-medium bg-slate-100 text-slate-600 border border-slate-200/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                        <span>Archived or Historical Record</span>
                    </div>
                </div>
            </body>
            </html>',
            200,
            ['Content-Type' => 'text/html']
        );
    }

    public function uploadFile(Request $request, $id)
    {
        $researchTitle = Research_title::findOrFail($id);
        $this->authorizeReviewerAssignment($researchTitle);

        $request->validate([
            'category' => 'required|string',
            'files' => 'required|array',
            'files.*' => 'file|mimes:pdf,doc,docx|max:20480'
        ], [
            'files.*.mimes' => 'Evaluation files must be in PDF, DOC, or DOCX format.',
            'files.*.max' => 'Each evaluation file must not exceed 20MB in size.'
        ]);

        DB::transaction(function () use ($request, $id) {
            foreach ($request->file('files') as $file) {
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
            }
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Evaluation Documents Uploaded Successfully',
            ]);
        }

        return back()->with('success', 'Evaluation Documents Uploaded Successfully');
    }

    public function deleteFile(Request $request, $id)
    {
        $file = researcher_files::findOrFail($id);

        $researchTitle = $file->effective_research_title;
        if (!$researchTitle && $file->research_title_id) {
            $researchTitle = Research_title::find($file->research_title_id);
        }

        if ($researchTitle) {
            $this->authorizeReviewerAssignment($researchTitle);
        }

        if ($file->uploaded_by !== Auth::id() && !in_array(Auth::user()->role ?? '', ['admin', 'superadmin'])) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized deletion.'], 403);
            }
            return back()->with('error', 'Unauthorized deletion.');
        }

        if (!str_starts_with($file->category, 'Reviewer Uploads')) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Cannot delete non-evaluation files.'], 403);
            }
            return back()->with('error', 'Cannot delete non-evaluation files.');
        }

        DB::transaction(function () use ($file) {
            // Standardize file path and safely delete physical file
            $cleanPath = ltrim(str_replace(['storage/', 'public/'], '', $file->filepath), '/');

            if (Storage::disk('public_uploads')->exists($cleanPath)) {
                Storage::disk('public_uploads')->delete($cleanPath);
            } elseif (Storage::disk('public')->exists($cleanPath)) {
                Storage::disk('public')->delete($cleanPath);
            } elseif (file_exists(public_path($file->filepath))) {
                @unlink(public_path($file->filepath));
            }

            $file->delete();
        });

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Evaluation document removed successfully.');
    }

    public function completeReview(Request $request, $id)
    {
        $submission = Research_title::findOrFail($id);
        $this->authorizeReviewerAssignment($submission);

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

        if ($isReEvaluation) {
            $request->validate([
                'review_decision' => 'required|string|in:Approved,Minor revision/s required,Major revision/s required,Disapproved',
                'scientific_soundness' => 'nullable|string|max:5000',
                'ethical_issues' => 'nullable|string|max:5000',
                'icf_issues' => 'nullable|string|max:5000',
                'summary_of_issues' => 'nullable|string|max:5000',
                'remarks' => 'nullable|string|max:2000',
                'file_remarks' => 'nullable|array',
                'file_remarks.*' => 'nullable|string|max:2000',
            ]);
        } else {
            $request->validate([
                'suggested_review_type' => 'required|string|in:Exempt Review,Expedited Review,Full Board Review',
                'file_remarks' => 'nullable|array',
                'file_remarks.*' => 'nullable|string|max:2000',
            ]);
        }

        $allDone = false;

        DB::transaction(function () use ($request, $id, $isReEvaluation, &$allDone) {
            // Lock submission row for concurrent safety
            $submission = Research_title::lockForUpdate()->findOrFail($id);

            // Attach suggested review type to latest evaluation document uploaded by this reviewer
            $myUploads = $submission->adminFiles()
                ->where('uploaded_by', Auth::id())
                ->where('category', 'like', 'Reviewer Uploads%')
                ->latest()
                ->get();

            $latestUpload = $myUploads->first();
            if ($latestUpload && $request->filled('suggested_review_type')) {
                $latestUpload->suggested_review_type = $request->input('suggested_review_type');
                $latestUpload->save();
            }

            // Save per-file remarks submitted from modal
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
            if ($request->filled('review_decision')) {
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

            // Mark this reviewer\'s assignment as completed in pivot
            if ($submission->reviewers()->where('users.id', Auth::id())->exists()) {
                $submission->reviewers()->updateExistingPivot(Auth::id(), ['status' => 'Completed']);
            } else {
                // Attach if legacy JSON assignee without pivot row
                $submission->reviewers()->attach(Auth::id(), ['status' => 'Completed', 'role' => 'Primary Reviewer']);
            }

            // Only mark overall submission as 'Reviewed' when ALL assigned reviewers are done
            $allPivotDone = $submission->reviewers()->wherePivot('status', '!=', 'Completed')->doesntExist();

            // Also check if any reviewer from legacy assigned_reviewers hasn\'t completed
            $assignedJson = $submission->assigned_reviewers;
            $allJsonDone = true;
            if (is_array($assignedJson) && count($assignedJson) > 0) {
                $completedIds = $submission->reviewers()->wherePivot('status', 'Completed')->pluck('users.id')->map(fn($uid) => (string)$uid)->toArray();
                foreach ($assignedJson as $revId) {
                    if (!in_array((string)$revId, $completedIds)) {
                        $allJsonDone = false;
                        break;
                    }
                }
            }

            $allDone = $allPivotDone && $allJsonDone;
            if ($allDone) {
                $submission->Status = 'Reviewed';
            } else {
                // Keep active status so remaining reviewers can still work
                if (!in_array($submission->Status, ['Under Review', 'Reviewing Revisions'])) {
                    $submission->Status = 'Under Review';
                }
            }
            $submission->save();
        });

        // Redirect based on context
        if ($isReEvaluation) {
            return redirect()->route('reviewer.reevaluation')->with('success', 'Re-evaluation completed. Waiting for other reviewers.');
        }

        if ($allDone) {
            return redirect()->route('reviewer.reviewed_titles')->with('success', 'All reviewers have completed. Protocol marked as Reviewed!');
        }
        return redirect()->route('reviewer.reviewed_titles')->with('success', 'Your review is complete. Waiting for other reviewers to finish.');
    }

    public function saveFileRemark(Request $request, $fileId)
    {
        $request->validate(['remarks' => 'nullable|string|max:2000']);

        $remark = trim($request->input('remarks', ''));

        $file = \App\Models\researcher_files::findOrFail($fileId);

        // Explicitly resolve the research title from the file
        $researchTitle = $file->effective_research_title;
        if (!$researchTitle && $file->research_title_id) {
            $researchTitle = Research_title::find($file->research_title_id);
        }

        if (!$researchTitle) {
            return response()->json(['success' => false, 'message' => 'Associated protocol not found.'], 404);
        }

        $this->authorizeReviewerAssignment($researchTitle);

        DB::transaction(function () use ($fileId, $remark, $researchTitle) {
            if ($remark === '') {
                // Delete existing remark if cleared
                \App\Models\ReviewerFileRemark::where('reviewer_id', Auth::id())
                    ->where('file_id', $fileId)
                    ->delete();
            } else {
                \App\Models\ReviewerFileRemark::updateOrCreate(
                    ['reviewer_id' => Auth::id(), 'file_id' => $fileId],
                    ['remarks' => $remark, 'research_title_id' => $researchTitle->id]
                );
            }
        });

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
            ->with(['researcher.user'])
            ->latest()
            ->get();

        return view('reviewer.reviewed_titles', compact('titles'));
    }
}
