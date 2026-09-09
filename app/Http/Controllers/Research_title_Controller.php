<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Research_title;
use App\Models\researcher_files;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\DocumentRequirement;
use App\Models\SubmissionFeedback;
use App\Models\TitleLog;

class Research_title_Controller extends Controller
{
    public function showSubmit()
    {
        $requirements = DocumentRequirement::all();
        $categories = \App\Models\ResearchCategory::where('active', true)->orderBy('created_at', 'asc')->get();
        return view('submit', compact('requirements', 'categories'));
    }






    public function submitTitle(Request $request)
    {
        $user = Auth::user();

        // 1. Base Validation
        $rules = [
            'Study_Protocol_title' => 'required|string|max:255',
            'Research_Category' => 'required|string|max:255',
            'research_type' => 'required|string|max:255',
            'other_category' => 'nullable|string|max:255',
            'project_type' => 'required|in:Funded Research,Course Requirement',
            'funding_type' => 'nullable|string|max:255',
            'course_type' => 'nullable|string|max:255',
        ];

        // Adviser is mandatory only for Course Requirement
        if ($request->input('project_type') === 'Course Requirement') {
            $rules['Adviser'] = 'required|string|max:255';
        } else {
            $rules['Adviser'] = 'nullable|string|max:255';
        }

        // 2. Dynamic Validation for Files
        $requirements = DocumentRequirement::all();
        $customAttributes = [
            'Study_Protocol_title' => 'Study Protocol Title',
            'Research_Category' => 'Research Category',
            'research_type' => 'Research Type',
            'project_type' => 'Project Type',
            'Adviser' => 'Adviser Name',
        ];
        $customMessages = [];

        foreach ($requirements as $req) {
            $field = 'files.' . $req->id;
            $customAttributes[$field] = $req->name;
            $customAttributes[$field . '.*'] = $req->name;

            // Build Validation Rules
            $fileRules = ['file', 'max:25600']; // Max 25MB

            // Mime Types
            $mimes = [];
            $types = explode(',', $req->file_type);
            foreach ($types as $type) {
                $type = trim($type);
                if (strcasecmp($type, 'PDF') === 0)
                    $mimes[] = 'pdf';
                if (strcasecmp($type, 'Word') === 0)
                    array_push($mimes, 'doc', 'docx');
                if (strcasecmp($type, 'Others') === 0)
                    array_push($mimes, 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp');
            }
            if (!empty($mimes)) {
                $mimes = array_values(array_unique($mimes));
                $fileRules[] = 'mimes:' . implode(',', $mimes);
            }

            $readableMimes = !empty($mimes) ? implode(', ', array_map('strtoupper', $mimes)) : 'PDF, DOC, DOCX';
            $customMessages[$field . '.required'] = "The {$req->name} document is required.";
            $customMessages[$field . '.mimes'] = "The {$req->name} must be a file of type: {$readableMimes}.";
            $customMessages[$field . '.max'] = "The {$req->name} must not exceed 25MB.";
            $customMessages[$field . '.*.mimes'] = "Each file for {$req->name} must be of type: {$readableMimes}.";
            $customMessages[$field . '.*.max'] = "Each file for {$req->name} must not exceed 25MB.";

            // Required / Array checks
            if ($req->is_multiple) {
                if ($req->is_required) {
                    $rules[$field] = 'required|array';
                } else {
                    $rules[$field] = 'nullable|array';
                }
                $rules[$field . '.*'] = $fileRules;
            } else {
                if ($req->is_required) {
                    $rules[$field] = array_merge(['required'], $fileRules);
                } else {
                    $rules[$field] = array_merge(['nullable'], $fileRules);
                }
            }
        }

        $validated = $request->validate($rules, $customMessages, $customAttributes);


        // Handle "Other" category
        $finalCategory = $validated['Research_Category'];
        if ($finalCategory === 'Other' && !empty($validated['other_category'])) {
            $finalCategory = $validated['other_category'];
        }

        // Look up the fee based on the category
        $fee = 0.00;
        if ($finalCategory !== 'Other') {
            $catRecord = \App\Models\ResearchCategory::where('name', $finalCategory)->first();
            if ($catRecord) {
                $fee = $catRecord->fee;
            }
        }

        // ✅ Create research title and attach files atomically
        DB::transaction(function () use ($validated, $finalCategory, $fee, $user, $request, $requirements) {
            $research = Research_title::create([
                'Study_Protocol_title' => $validated['Study_Protocol_title'],
                'Research_Category' => $finalCategory,
                'research_type' => $validated['research_type'],
                'category_fee_at_submission' => $fee,
                'Created_by' => $user->first_name . ' ' . $user->last_name,
                'researcher_id' => $user->researcher->id,
                'project_type' => $validated['project_type'],
                'funding_type' => $validated['funding_type'] ?? null,
                'course_type' => $validated['course_type'] ?? null,
                'Adviser' => $validated['Adviser'] ?? null,
            ]);

            // Log OR upload (OR Number might not be submitted if it's handled as a file requirement instead)
            $orNumberText = isset($request->or_number) ? " #" . $request->or_number : "";

            TitleLog::create([
                'research_title_id' => $research->id,
                'user_id' => Auth::id(),
                'action' => 'Official Receipt Uploaded',
                'description' => "Uploaded Official Receipt{$orNumberText} at submission. Pending Admin verification.",
            ]);

            $uploadedFileIds = [];

            // ✅ Store documents
            foreach ($requirements as $req) {
                $fieldKey = 'files.' . $req->id;

                if ($request->hasFile($fieldKey)) {
                    $files = $request->file($fieldKey);

                    // Unify to array for processing
                    if (!is_array($files)) {
                        $files = [$files];
                    }

                    foreach ($files as $file) {
                        // Generate category specific filename
                        // Category = Requirement Name
                        $filename = time() . '_' . \Illuminate\Support\Str::slug($req->name) . '_' . $file->getClientOriginalName();
                        $path = $file->storeAs('uploads/research_files', $filename, 'public_uploads');

                        $fileRecord = researcher_files::create([
                            'filename' => $filename,
                            'filepath' => $path,
                            'filetype' => $file->getClientOriginalExtension(),
                            'category' => $req->name, // Storing human-readable requirement name
                        ]);

                        $uploadedFileIds[] = $fileRecord->id;
                    }
                }
            }

            // ✅ Attach files to pivot table
            $research->files()->attach($uploadedFileIds);
        });

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Research title and all required documents successfully submitted!',
                'redirect' => route('home'),
            ]);
        }

        return redirect(route('home'))->with('success', 'Research title and all required documents successfully submitted!');
    }



    public function showTitles()
    {
        $user = Auth::user();
        if (!$user->researcher) {
            return redirect()->back()->with('error', 'You are not registered as a researcher.');
        }
        $titles = Research_title::with(['files', 'adminFiles', 'appointment', 'titleLogs.user'])
            ->where('researcher_id', $user->researcher->id)
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        // Preload assigned reviewer users to prevent N+1 queries in details modal
        $allReviewerIds = $titles->pluck('assigned_reviewers')->flatten()->filter()->unique();
        $reviewers = \App\Models\User::whereIn('id', $allReviewerIds)->get()->keyBy('id');

        return view('home', compact('titles', 'reviewers'));
    }

    // Show all files for a specific research title
    public function manageFiles($id)
    {
        $researchTitle = Research_title::with(['files.reviewerRemarks.reviewer', 'adminFiles', 'titleLogs.user'])->findOrFail($id);
        $user = Auth::user();

        // Security: Ensure logged-in researcher owns this protocol
        if (!$user->researcher || $researchTitle->researcher_id !== $user->researcher->id) {
            abort(403, 'Unauthorized. You do not have permission to view this research protocol.');
        }

        $requirements = DocumentRequirement::all();

        // Fetch stage-specific general remarks to display to the researcher
        $stageRemark = null;
        $status = $researchTitle->Status;

        if (in_array($status, ['Incomplete', 'Pending', 'Pending (Initial Intake)'])) {
            // Initial Intake stage — show latest admin_deficiency remark
            $stageRemark = SubmissionFeedback::where('research_title_id', $id)
                ->where('type', 'admin_deficiency')
                ->latest()
                ->first();
        } elseif (in_array($status, ['Incomplete Hardcopy', 'Incomplete - Awaiting Hardcopy'])) {
            // Hardcopy stage — show latest hardcopy_deficiency remark
            $stageRemark = SubmissionFeedback::where('research_title_id', $id)
                ->where('type', 'hardcopy_deficiency')
                ->latest()
                ->first();
        } elseif ($status === 'Waiting for Revision') {
            // Revision stage — show latest admin_deliberation notes
            $stageRemark = SubmissionFeedback::where('research_title_id', $id)
                ->where('type', 'admin_deliberation')
                ->latest()
                ->first();
        }

        return view('researcher_files', compact('researchTitle', 'requirements', 'stageRemark'));
    }

    public function updateFile(Request $request, $id)
    {
        $user = Auth::user();
        $research = Research_title::findOrFail($id);

        // Security check: ensure researcher owns this research title
        if (!$user->researcher || $research->researcher_id !== $user->researcher->id) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($research->Status, ['Incomplete', 'Pending', 'Pending (Initial Intake)'])) {
            if ($request->expectsJson())
                return response()->json(['error' => 'You can only update files when the protocol status is Incomplete or Pending.'], 403);
            abort(403, 'You can only update files when the protocol status is Incomplete or Pending.');
        }

        $request->validate([
            'file' => 'required|file|max:25600',
            'file_id' => 'required|integer',
        ]);

        $oldResearchFile = Researcher_files::findOrFail($request->file_id);

        // Security check: verify the file actually belongs to this research title
        $belongsToResearch = ($oldResearchFile->research_title_id == $id)
            || $research->files()->where('researcher_files.id', $oldResearchFile->id)->exists();

        if (!$belongsToResearch) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized. File does not belong to this protocol.'], 403);
            }
            abort(403, 'File does not belong to this protocol.');
        }

        // Store new file on disk
        $path = $request->file('file')->store('uploads/research_files', 'public_uploads');

        // Wrap database mutations in transaction
        $newFileRecord = DB::transaction(function () use ($research, $oldResearchFile, $request, $path) {
            // Delete the old active file completely when updating directly
            if ($oldResearchFile->revision_number === null) {
                Storage::disk('public_uploads')->delete(str_replace('storage/', '', $oldResearchFile->filepath));
                $research->files()->detach($oldResearchFile->id);
                $oldResearchFile->delete();
            }

            // Create new replacement active file record
            $newRecord = Researcher_files::create([
                'research_title_id' => $research->id,
                'filename' => $request->file('file')->getClientOriginalName(),
                'filepath' => $path,
                'filetype' => $request->file('file')->getClientOriginalExtension(),
                'category' => $oldResearchFile->category,
                'revision_number' => null,
            ]);

            $research->files()->attach($newRecord->id);

            return $newRecord;
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'file' => [
                    'id' => $newFileRecord->id,
                    'filename' => $newFileRecord->filename,
                    'filepath' => asset($newFileRecord->filepath),
                    'filetype' => $newFileRecord->filetype,
                    'created_at' => $newFileRecord->created_at ? $newFileRecord->created_at->timezone('Asia/Manila')->format('F d, Y \a\t h:i A') : 'just now',
                    'updated_at' => $newFileRecord->updated_at ? $newFileRecord->updated_at->timezone('Asia/Manila')->format('F d, Y \a\t h:i A') : 'just now'
                ]
            ]);
        }

        return redirect()->back()->with('success', 'File updated successfully!');
    }

    public function addMissingFile(Request $request, $id)
    {
        $user = Auth::user();
        $researchTitle = Research_title::findOrFail($id);

        // Security check: ensure researcher owns this research title
        if (!$user->researcher || $researchTitle->researcher_id !== $user->researcher->id) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            abort(403, 'Unauthorized.');
        }

        if (!in_array($researchTitle->Status, ['Incomplete', 'Pending', 'Pending (Initial Intake)'])) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'You can only upload missing files when the protocol status is Incomplete or Pending.'], 403);
            }
            abort(403, 'You can only upload missing files when the protocol status is Incomplete or Pending.');
        }

        $request->validate([
            'file' => 'required|file|max:25600',
            'category' => 'required|string',
        ]);

        $path = $request->file('file')->store('uploads/research_files', 'public_uploads');

        $newFileRecord = DB::transaction(function () use ($researchTitle, $request, $path) {
            $record = researcher_files::create([
                'research_title_id' => $researchTitle->id,
                'filename' => $request->file('file')->getClientOriginalName(),
                'filepath' => $path,
                'filetype' => $request->file('file')->getClientOriginalExtension(),
                'category' => $request->category,
                'revision_number' => null, // Directly attaching to original files
            ]);

            $researchTitle->files()->attach($record->id);
            return $record;
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'file' => [
                    'id' => $newFileRecord->id,
                    'filename' => $newFileRecord->filename,
                    'filepath' => asset($newFileRecord->filepath),
                    'filetype' => $newFileRecord->filetype,
                    'created_at' => $newFileRecord->created_at ? $newFileRecord->created_at->timezone('Asia/Manila')->format('F d, Y \a\t h:i A') : 'just now',
                    'updated_at' => $newFileRecord->updated_at ? $newFileRecord->updated_at->timezone('Asia/Manila')->format('F d, Y \a\t h:i A') : 'just now'
                ]
            ]);
        }

        return back()->with('success', 'Document added successfully!');
    }

    public function uploadRevisionDocument(Request $request, $id)
    {
        $user = Auth::user();
        $researchTitle = Research_title::findOrFail($id);

        // Security check: ensure researcher owns this research title
        if (!$user->researcher || $researchTitle->researcher_id !== $user->researcher->id) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            abort(403, 'Unauthorized.');
        }

        if (!in_array($researchTitle->Status, ['Waiting for Revision', 'Incomplete'])) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'You can only upload revision documents when the protocol is Waiting for Revision or Incomplete.'], 403);
            }
            abort(403, 'Protocol is not currently accepting revision uploads.');
        }

        $request->validate([
            'file' => 'required|file|max:25600',
            'category' => 'required|string',
        ]);

        $path = $request->file('file')->store('uploads/research_files', 'public_uploads');

        $newFileRecord = DB::transaction(function () use ($researchTitle, $request, $path) {
            $record = Researcher_files::create([
                'research_title_id' => $researchTitle->id,
                'filename' => $request->file('file')->getClientOriginalName(),
                'filepath' => $path,
                'filetype' => $request->file('file')->getClientOriginalExtension(),
                'category' => $request->category,
                'revision_number' => -1, // -1 means In-Progress Workspace
            ]);

            $researchTitle->files()->attach($record->id);
            return $record;
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'file' => [
                    'id' => $newFileRecord->id,
                    'filename' => $newFileRecord->filename,
                    'filetype' => collect(explode('.', $newFileRecord->filename))->last(), // Ensure accurate label
                    'category' => $newFileRecord->category,
                    'isPdf' => strtolower($newFileRecord->filetype) === 'pdf',
                    'deleteUrl' => route('delete.revision.document', $newFileRecord->id),
                    'created_at' => $newFileRecord->created_at ? $newFileRecord->created_at->timezone('Asia/Manila')->format('F d, Y \a\t h:i A') : 'just now'
                ]
            ]);
        }

        return back()->with('success', 'Document added to draft workspace.');
    }

    public function deleteRevisionDocument($file_id)
    {
        $file = Researcher_files::findOrFail($file_id);
        $user = Auth::user();

        // Safe resolution via direct ID or pivot relation
        $researchTitle = $file->research_title_id
            ? Research_title::find($file->research_title_id)
            : Research_title::whereHas('files', function ($q) use ($file_id) {
                $q->where('researcher_files.id', $file_id);
            })->first();

        if (!$researchTitle || !$user->researcher || $researchTitle->researcher_id !== $user->researcher->id) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            abort(403, 'Unauthorized.');
        }

        if ($file->revision_number != -1) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Not a draft document'], 403);
            }
            abort(403, 'Can only delete files from the active draft workspace.');
        }

        DB::transaction(function () use ($researchTitle, $file) {
            Storage::disk('public_uploads')->delete(str_replace('storage/', '', $file->filepath));
            $researchTitle->files()->detach($file->id);
            $file->delete();
        });

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Document removed from draft workspace.');
    }

    public function viewRecommendationLetter($id)
    {
        $researchTitle = Research_title::findOrFail($id);
        $user = Auth::user();

        // Security: Ensure user owns the title
        if (!$user->researcher || $researchTitle->researcher_id !== $user->researcher->id) {
            abort(403, 'Unauthorized action.');
        }

        // Find the recommendation letter file across files, adminFiles, or direct ID
        $file = $researchTitle->files()->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter', 'Archived Result of Review'])->latest()->first()
            ?? $researchTitle->adminFiles()->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter', 'Archived Result of Review'])->latest()->first()
            ?? researcher_files::where('research_title_id', $id)->whereIn('filetype', ['Result of Review (Admin Generated)', 'recommendation letter', 'Archived Result of Review'])->latest()->first();

        if (!$file) {
            return back()->with('error', 'Recommendation letter not found.');
        }

        return $this->serveFile(request(), $file->id);
    }

    public function serveFile($request, $id = null)
    {
        if ($id === null) {
            $id = $request;
            $request = request();
        } elseif (!($request instanceof Request)) {
            $id = $request;
            $request = request();
        }

        $file = researcher_files::findOrFail($id);
        $user = Auth::user();

        // Security check: ensure researcher owns this research title
        $researchTitle = $file->research_title_id 
            ? Research_title::find($file->research_title_id)
            : Research_title::whereHas('files', function ($q) use ($id) {
                $q->where('researcher_files.id', $id);
            })->first();

        if ($researchTitle && $user->researcher && $researchTitle->researcher_id !== $user->researcher->id) {
            abort(403, 'Unauthorized.');
        }

        $path = str_replace('storage/', '', $file->filepath);
        $isDownload = $request->boolean('download');
        $extension = strtolower(pathinfo($file->filepath, PATHINFO_EXTENSION));
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
        ];
        $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';

        $safeFilename = str_replace(['"', "\r", "\n"], '', $file->filename);
        $disposition = $isDownload ? 'attachment' : 'inline';
        $headers = [
            'Content-Type' => $mimeType,
            'Content-Disposition' => $disposition . '; filename="' . $safeFilename . '"',
        ];

        $respondWithFile = function ($fullPath) use ($isDownload, $safeFilename, $headers) {
            if ($isDownload) {
                return response()->download($fullPath, $safeFilename, $headers);
            }
            return response()->file($fullPath, $headers);
        };

        // 1. Check Storage (Public Disk)
        if (Storage::disk('public')->exists($path)) {
            return $respondWithFile(storage_path('app/public/' . $path));
        }

        // 2. Check public_uploads disk
        if (Storage::disk('public_uploads')->exists($path)) {
            $candidatePath = public_path($path);
            if (file_exists($candidatePath)) {
                return $respondWithFile($candidatePath);
            }
            if (file_exists(public_path('uploads/research_files/' . basename($path)))) {
                return $respondWithFile(public_path('uploads/research_files/' . basename($path)));
            }
        }

        // 3. Check Public Directory (Direct Access)
        $publicPath = public_path($file->filepath);
        if (file_exists($publicPath)) {
            return $respondWithFile($publicPath);
        }

        // 4. Check Storage Path directly (Absolute)
        $storagePath = storage_path('app/public/' . $path);
        if (file_exists($storagePath)) {
            return $respondWithFile($storagePath);
        }

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
                        <span>Archived or Hardcopy Record</span>
                    </div>
                </div>
            </body>
            </html>',
            200,
            ['Content-Type' => 'text/html']
        );
    }

    public function submitRevisions(Request $request, $id)
    {
        $user = Auth::user();
        $researchTitle = Research_title::findOrFail($id);

        // Security check: ensure researcher owns this research title
        if (!$user->researcher || $researchTitle->researcher_id !== $user->researcher->id) {
            abort(403, 'Unauthorized action.');
        }

        // Validate message if needed (optional)
        $request->validate([
            'revision_message' => 'nullable|string|max:1000',
        ]);

        if (!in_array($researchTitle->Status, ['Waiting for Revision', 'Incomplete'])) {
            return back()->with('error', 'Unable to submit revisions. Current status: ' . $researchTitle->Status);
        }

        $isIncomplete = $researchTitle->Status === 'Incomplete';

        // Wrapped in atomic database transaction with row-level locking
        $successMsg = DB::transaction(function () use ($id, $user, $request, $isIncomplete) {
            $protocol = Research_title::where('id', $id)->lockForUpdate()->firstOrFail();

            // Only enforce the Draft Workspace check for formal Revisions
            if (!$isIncomplete) {
                // Check if any files have been uploaded into the draft workspace
                $hasUpdatedFiles = Researcher_files::where(function ($q) use ($id) {
                        $q->where('research_title_id', $id)
                          ->orWhereHas('researchTitles', fn($sq) => $sq->where('research_title_information.id', $id));
                    })
                    ->where('revision_number', -1)
                    ->exists();

                if (!$hasUpdatedFiles) {
                    throw new \InvalidArgumentException('You must upload at least one document to your Revision Workspace before submitting corrections.');
                }

                // Group all draft workspace files into a formal new Revision Folder
                $currentMax = Researcher_files::where(function ($q) use ($id) {
                        $q->where('research_title_id', $id)
                          ->orWhereHas('researchTitles', fn($sq) => $sq->where('research_title_information.id', $id));
                    })
                    ->where('revision_number', '>', 0)
                    ->max('revision_number') ?? 0;
                $newRevisionNumber = $currentMax + 1;

                Researcher_files::where(function ($q) use ($id) {
                        $q->where('research_title_id', $id)
                          ->orWhereHas('researchTitles', fn($sq) => $sq->where('research_title_information.id', $id));
                    })
                    ->where('revision_number', -1)
                    ->update(['revision_number' => $newRevisionNumber]);
            }

            // Determine new status
            $newStatus = $isIncomplete ? 'Incomplete Resubmitted' : 'Revision Submitted';

            // Create Submission Feedback (User Correction) & Revision Log for Admin View
            if ($request->revision_message) {
                SubmissionFeedback::create([
                    'research_title_id' => $protocol->id,
                    'user_id' => $user->id,
                    'type' => 'user_correction',
                    'message' => $request->revision_message,
                ]);

                \App\Models\RevisionLog::create([
                    'research_title_id' => $protocol->id,
                    'user_id' => $user->id,
                    'message' => $request->revision_message,
                ]);
            } else {
                \App\Models\RevisionLog::create([
                    'research_title_id' => $protocol->id,
                    'user_id' => $user->id,
                    'message' => $isIncomplete ? 'Resubmitted initial intake files without additional notes.' : 'Resubmitted without additional notes.',
                ]);
            }

            $protocol->Status = $newStatus;

            // Reset all assigned reviewers back to Pending so the protocol appears on their dashboard
            if (!$isIncomplete) {
                foreach ($protocol->reviewers as $reviewer) {
                    $protocol->reviewers()->updateExistingPivot($reviewer->id, ['status' => 'Pending']);
                }
            }

            $protocol->save();

            return $isIncomplete ? 'Corrections submitted successfully! Document history synced.' : 'Revisions submitted successfully! Document history synced.';
        });

        return redirect()->route('home')->with('success', $successMsg);
    }
}

