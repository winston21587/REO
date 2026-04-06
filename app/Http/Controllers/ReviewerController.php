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
        
        // Find titles where the user ID is in the assigned_reviewers JSON array
        // We handle both integer and string storage in JSON arrays just in case
        $titles = Research_title::whereJsonContains('assigned_reviewers', (string)$userId)
                    ->orWhereJsonContains('assigned_reviewers', $userId)
                    ->latest()
                    ->get();

        return view('reviewer.dashboard', compact('titles'));
    }

    public function viewFiles($id)
    {
        $researchTitle = Research_title::with(['researcher.user', 'files', 'adminFiles'])->findOrFail($id);
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
        
        // Ensure consistent path usage with Admin logic
        $path = $file->store('uploads/research_files', 'public_uploads');

        researcher_files::create([
            'research_title_id' => $id,
            'filename' => $file->getClientOriginalName(),
            'filepath' => 'uploads/research_files/' . basename($path),
            'filetype' => $file->getClientOriginalExtension(),
            'uploaded_by' => Auth::id(),
            'category' => 'Reviewer Uploads - ' . $request->input('category'),
            'revision_number' => 0
        ]);

        return back()->with('success', 'Evaluation Document Uploaded Successfully');
    }

    public function completeReview(Request $request, $id)
    {
        $submission = Research_title::findOrFail($id);
        
        // Prevent completing if no reviewer uploads exist
        $hasUploads = $submission->adminFiles()
            ->where('uploaded_by', Auth::id())
            ->where('category', 'like', 'Reviewer Uploads%')
            ->exists();

        if (!$hasUploads) {
            return back()->withErrors(['error' => 'You must upload at least one evaluation document before completing the review.']);
        }

        $submission->Status = 'Reviewed';
        $submission->save();

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
            // Must have successfully deposited an evaluation upload globally
            ->whereHas('adminFiles', function($query) use ($userId) {
                $query->where('uploaded_by', $userId)
                      ->where('category', 'like', 'Reviewer Uploads%');
            })
            ->latest()
            ->get();

        return view('reviewer.reviewed_titles', compact('titles'));
    }
}
