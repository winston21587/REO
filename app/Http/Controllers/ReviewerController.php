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

        if (in_array($user->role ?? '', ['admin', 'superadmin', 'super_admin'], true)) {
            return;
        }

        $userId = $user->id;

        // 1. Check modern pivot assignment
        if ($title->reviewers()->where('users.id', $userId)->exists()) {
            return;
        }

        // 2. Check legacy JSON column assigned_reviewers
        $assigned = $title->assigned_reviewers;
        if (is_array($assigned) && in_array((string) $userId, array_map('strval', $assigned), true)) {
            return;
        } elseif (is_string($assigned)) {
            $decoded = json_decode($assigned, true);
            if (is_array($decoded) && in_array((string) $userId, array_map('strval', $decoded), true)) {
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

        // Path Traversal Defense: Reject relative traversal sequences or null bytes
        if (str_contains($file->filepath, '..') || str_contains($file->filepath, "\0")) {
            abort(403, 'Invalid file path sequence detected.');
        }

        $path = ltrim(str_replace('storage/', '', $file->filepath), '/');

        $respondWithFile = function (string $fullPath) use ($file) {
            $realPath = realpath($fullPath);
            $allowedStorage = realpath(storage_path('app/public'));
            $allowedPublic = realpath(public_path());

            // Ensure canonical path stays strictly inside approved storage directories
            $isWithinAllowedStorage = $allowedStorage && $realPath && str_starts_with($realPath, $allowedStorage);
            $isWithinAllowedPublic = $allowedPublic && $realPath && str_starts_with($realPath, $allowedPublic);

            if (!$realPath || (!$isWithinAllowedStorage && !$isWithinAllowedPublic)) {
                abort(403, 'Unauthorized file path access.');
            }

            $mimeType = \Illuminate\Support\Facades\File::mimeType($realPath) ?: 'application/octet-stream';
            $disposition = request()->boolean('download') ? 'attachment' : 'inline';

            // Sanitize filename for Content-Disposition against response splitting / CRLF injection
            $safeHeaderFilename = str_replace(["\r", "\n", '"', ';', '\\'], '', $file->filename);

            return response()->file($realPath, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => "{$disposition}; filename=\"{$safeHeaderFilename}\"",
                'X-Content-Type-Options' => 'nosniff',
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
            <html lang="en" style="height:100%;min-height:100%;margin:0;padding:0;">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <title>Document Unavailable</title>
                <script src="https://cdn.tailwindcss.com"></script>
                <style>
                    html, body { height: 100% !important; min-height: 100% !important; margin: 0 !important; padding: 0 !important; }
                    body { display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; }
                </style>
            </head>
            <body class="bg-slate-50/80 flex flex-col items-center justify-center h-full min-h-full p-6 font-sans text-slate-700 antialiased" style="height:100%;min-height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;margin:0;padding:1.5rem;box-sizing:border-box;">
                <div class="max-w-md w-full bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 text-center shadow-xs">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 border border-slate-200/80 flex items-center justify-center mx-auto mb-3.5 text-slate-500 shadow-2xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200/80 mb-2">
                        Archived or Hardcopy Record
                    </span>
                    <h3 class="text-sm font-bold text-slate-900 mb-1">Document Unavailable on Disk</h3>
                    <p class="text-xs text-slate-500 mb-4 leading-relaxed break-all font-mono bg-slate-50 py-1.5 px-2.5 rounded-lg border border-slate-200/60 max-w-sm mx-auto">' . htmlspecialchars($file->filename) . '</p>
                    <p class="text-[11px] text-slate-400 max-w-xs mx-auto leading-normal">
                        This document is registered in the institutional evaluation record. The physical binary file is stored in hardcopy or offline archives.
                    </p>
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
            'category' => 'required|string|max:100',
            'files' => 'required|array|min:1|max:10',
            'files.*' => 'file|mimes:pdf,doc,docx|max:20480'
        ], [
            'category.max' => 'Category cannot exceed 100 characters.',
            'files.max' => 'You cannot upload more than 10 files at once.',
            'files.*.mimes' => 'Evaluation files must be in PDF, DOC, or DOCX format.',
            'files.*.max' => 'Each evaluation file must not exceed 20MB in size.'
        ]);

        $allowedExtensions = ['pdf', 'doc', 'docx'];

        DB::transaction(function () use ($request, $id, $allowedExtensions) {
            foreach ($request->file('files') as $file) {
                $originalExt = strtolower($file->getClientOriginalExtension());
                if (!in_array($originalExt, $allowedExtensions, true)) {
                    abort(422, 'Invalid file format detected.');
                }

                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // Sanitize filename against traversal characters, null bytes, and non-alphanumeric symbols
                $sanitizedBaseName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $originalName);
                $sanitizedBaseName = substr($sanitizedBaseName, 0, 100);
                $modifiedName = $sanitizedBaseName . '_reviewer.' . $originalExt;

                // Random cryptographically secure token to prevent predictable filenames and race condition collisions
                $secureDiskName = time() . '_' . bin2hex(random_bytes(8)) . '_' . $modifiedName;
                $path = $file->storeAs('uploads/research_files', $secureDiskName, 'public_uploads');

                researcher_files::create([
                    'research_title_id' => $id,
                    'filename' => $modifiedName,
                    'filepath' => 'uploads/research_files/' . basename($path),
                    'filetype' => $originalExt,
                    'uploaded_by' => Auth::id(),
                    'category' => 'Reviewer Uploads - ' . strip_tags($request->input('category')),
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

        if (!$researchTitle) {
            abort(404, 'Associated research protocol not found.');
        }

        $this->authorizeReviewerAssignment($researchTitle);

        if ($file->uploaded_by !== Auth::id() && !in_array(Auth::user()->role ?? '', ['admin', 'superadmin', 'super_admin'], true)) {
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

        if (str_contains($file->filepath, '..') || str_contains($file->filepath, "\0")) {
            abort(403, 'Invalid file path sequence.');
        }

        DB::transaction(function () use ($file) {
            // Standardize file path and safely delete physical file
            $cleanPath = ltrim(str_replace(['storage/', 'public/'], '', $file->filepath), '/');

            if (Storage::disk('public_uploads')->exists($cleanPath)) {
                Storage::disk('public_uploads')->delete($cleanPath);
            } elseif (Storage::disk('public')->exists($cleanPath)) {
                Storage::disk('public')->delete($cleanPath);
            } elseif (file_exists(public_path($file->filepath))) {
                $realPublic = realpath(public_path($file->filepath));
                $allowedPublic = realpath(public_path());
                if ($realPublic && $allowedPublic && str_starts_with($realPublic, $allowedPublic)) {
                    @unlink($realPublic);
                }
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

            // Save per-file remarks submitted from modal with strict BOLA / IDOR protection
            $fileRemarks = $request->input('file_remarks', []);
            if (is_array($fileRemarks)) {
                // Get all valid file IDs belonging to this protocol
                $validFileIds = $submission->files->pluck('id')
                    ->merge($submission->adminFiles->pluck('id'))
                    ->map(fn($fid) => (int)$fid)
                    ->all();

                foreach ($fileRemarks as $fileId => $remark) {
                    $cleanFileId = (int)$fileId;
                    // BOLA Defense: Verify file belongs strictly to this protocol
                    if (!in_array($cleanFileId, $validFileIds, true)) {
                        continue;
                    }

                    $cleanRemark = trim(strip_tags((string)$remark));
                    if ($cleanRemark !== '') {
                        \App\Models\ReviewerFileRemark::updateOrCreate(
                            [
                                'reviewer_id' => Auth::id(),
                                'file_id' => $cleanFileId
                            ],
                            [
                                'remarks' => $cleanRemark,
                                'research_title_id' => $id
                            ]
                        );
                    }
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
        $cleanFileId = (int)$fileId;
        if ($cleanFileId <= 0) {
            return response()->json(['success' => false, 'message' => 'Invalid file identifier.'], 422);
        }

        $request->validate(['remarks' => 'nullable|string|max:2000']);

        $remark = trim(strip_tags($request->input('remarks', '')));

        $file = \App\Models\researcher_files::findOrFail($cleanFileId);

        // Explicitly resolve the research title from the file
        $researchTitle = $file->effective_research_title;
        if (!$researchTitle && $file->research_title_id) {
            $researchTitle = Research_title::find($file->research_title_id);
        }

        if (!$researchTitle) {
            return response()->json(['success' => false, 'message' => 'Associated protocol not found.'], 404);
        }

        $this->authorizeReviewerAssignment($researchTitle);

        DB::transaction(function () use ($cleanFileId, $remark, $researchTitle) {
            if ($remark === '') {
                // Delete existing remark if cleared
                \App\Models\ReviewerFileRemark::where('reviewer_id', Auth::id())
                    ->where('file_id', $cleanFileId)
                    ->delete();
            } else {
                \App\Models\ReviewerFileRemark::updateOrCreate(
                    ['reviewer_id' => Auth::id(), 'file_id' => $cleanFileId],
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
