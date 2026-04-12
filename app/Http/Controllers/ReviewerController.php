<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Research_title;
use App\Models\researcher_files;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewerController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Only show initial-review statuses; revision-related papers go to Re-Evaluation
        $revisionStatuses = ['Waiting for Revision', 'Revision Submitted', 'Reviewing Revisions'];

        $titles = Research_title::where(function($q) use ($userId) {
                $q->whereJsonContains('assigned_reviewers', (string)$userId)
                  ->orWhereJsonContains('assigned_reviewers', $userId);
            })
            ->where('Status', '!=', 'Reviewed')
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

        $titles = Research_title::where(function($q) use ($userId) {
                $q->whereJsonContains('assigned_reviewers', (string)$userId)
                  ->orWhereJsonContains('assigned_reviewers', $userId);
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
        if ($researchTitle->Status === 'Waiting for Revision') {
            $researchTitle->Status = 'Reviewing Revisions';
            $researchTitle->save();
        }
        $requirementsMap = \App\Models\DocumentRequirement::all()->keyBy('name')->toArray();
        $backUrl = url()->previous(route('reviewer.dashboard'));
        return view('reviewer.view_files', compact('researchTitle', 'backUrl', 'requirementsMap'));
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
        $latestUpload = $submission->adminFiles()
            ->where('uploaded_by', Auth::id())
            ->where('category', 'like', 'Reviewer Uploads%')
            ->latest()
            ->first();

        if ($latestUpload && $request->has('suggested_review_type')) {
            $latestUpload->suggested_review_type = $request->input('suggested_review_type');
            $latestUpload->save();
        }

        // Save Review Decision & Remarks (Re-Evaluation only)
        if ($request->has('review_decision')) {
            $submission->reviewer_decision = $request->input('review_decision');

            $msg = "Review Decision: " . $request->input('review_decision') . "\nRemarks: " . $request->input('remarks', 'None');
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
        }

        $submission->Status = 'Reviewed';
        $submission->save();

        // Redirect based on context
        if ($isReEvaluation) {
            return redirect()->route('reviewer.reevaluation')->with('success', 'Re-evaluation completed successfully!');
        }

        return redirect()->route('reviewer.dashboard')->with('success', 'Protocol review marked as complete!');
    }

    public function reviewedTitles()
    {
        $userId = Auth::id();
        
        $titles = Research_title::where(function($q) use ($userId) {
                // Must be legitimately assigned
                $q->whereJsonContains('assigned_reviewers', (string)$userId)
                  ->orWhereJsonContains('assigned_reviewers', $userId);
            })
            ->where('Status', 'Reviewed')
            ->latest()
            ->get();

        return view('reviewer.reviewed_titles', compact('titles'));
    }
}
