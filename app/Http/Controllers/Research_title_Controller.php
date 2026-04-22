<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Research_title;
use App\Models\researcher_files;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        foreach ($requirements as $req) {
            $field = 'files.' . $req->id;

            // Build Validation Rules
            $fileRules = ['file', 'max:25600']; // Max 25MB

            // Mime Types
            $mimes = [];
            $types = explode(',', $req->file_type);
            foreach ($types as $type) {
                $type = trim($type);
                if ($type === 'PDF')
                    $mimes[] = 'pdf';
                if ($type === 'Word')
                    array_push($mimes, 'doc', 'docx');
                if ($type === 'Others')
                    array_push($mimes, 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp');
            }
            if (!empty($mimes)) {
                $fileRules[] = 'mimes:' . implode(',', $mimes);
            }

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

        $validated = $request->validate($rules);


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

        // ✅ Create research title
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

        return redirect(route('home'))->with('success', 'Research title and all required documents successfully submitted!');
    }



    public function showTitles()
    {
        $user = Auth::user();
        if (!$user->researcher) {
            return redirect()->back()->with('error', 'You are not registered as a researcher.');
        }
        $titles = Research_title::with(['files', 'titleLogs.user'])
            ->where('researcher_id', $user->researcher->id)
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('home', compact('titles'));
    }

    // Show all files for a specific research title
    public function manageFiles($id)
    {
        $researchTitle = Research_title::with(['files.reviewerRemarks.reviewer', 'adminFiles', 'titleLogs.user'])->findOrFail($id);
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
        $request->validate([
            'file' => 'required|file|max:25600',
            'file_id' => 'required|integer',
        ]);

        $research = Research_title::findOrFail($id);
        if (!in_array($research->Status, ['Incomplete', 'Pending', 'Pending (Initial Intake)'])) {
            if ($request->expectsJson())
                return response()->json(['error' => 'You can only update files when the protocol status is Incomplete or Pending.'], 403);
            abort(403, 'You can only update files when the protocol status is Incomplete or Pending.');
        }

        $oldResearchFile = Researcher_files::findOrFail($request->file_id);

        // Store new file
        $path = $request->file('file')->store('uploads/research_files', 'public_uploads');

        // Original behavior: Delete the old active file completely when updating directly
        if ($oldResearchFile->revision_number === null) {
            Storage::disk('public_uploads')->delete(str_replace('storage/', '', $oldResearchFile->filepath));
            $oldResearchFile->delete();
        }

        // Create new replacement active file record
        $newFileRecord = Researcher_files::create([
            'research_title_id' => $oldResearchFile->research_title_id,
            'filename' => $request->file('file')->getClientOriginalName(),
            'filepath' => $path,
            'filetype' => $request->file('file')->getClientOriginalExtension(),
            'category' => $oldResearchFile->category,
            'revision_number' => null,
        ]);

        $research = Research_title::findOrFail($id);
        $research->files()->attach($newFileRecord->id);

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
        $request->validate([
            'file' => 'required|file|max:25600',
            'category' => 'required|string',
        ]);

        $researchTitle = Research_title::findOrFail($id);
        $user = Auth::user();
        if (!$user->researcher || $researchTitle->researcher_id !== $user->researcher->id) {
            if ($request->expectsJson())
                return response()->json(['error' => 'Unauthorized'], 403);
            abort(403);
        }

        if (!in_array($researchTitle->Status, ['Incomplete', 'Pending', 'Pending (Initial Intake)'])) {
            if ($request->expectsJson())
                return response()->json(['error' => 'You can only upload missing files when the protocol status is Incomplete or Pending.'], 403);
            abort(403, 'You can only upload missing files when the protocol status is Incomplete or Pending.');
        }

        $path = $request->file('file')->store('uploads/research_files', 'public_uploads');

        $newFileRecord = researcher_files::create([
            'research_title_id' => $id,
            'filename' => $request->file('file')->getClientOriginalName(),
            'filepath' => $path,
            'filetype' => $request->file('file')->getClientOriginalExtension(),
            'category' => $request->category,
            'revision_number' => null, // Directly attaching to original files
        ]);

        $researchTitle->files()->attach($newFileRecord->id);

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
        $request->validate([
            'file' => 'required|file|max:5120',
            'category' => 'required|string',
        ]);

        $researchTitle = Research_title::findOrFail($id);
        $user = Auth::user();
        if (!$user->researcher || $researchTitle->researcher_id !== $user->researcher->id) {
            if ($request->expectsJson())
                return response()->json(['error' => 'Unauthorized'], 403);
            abort(403);
        }

        $path = $request->file('file')->store('uploads/research_files', 'public_uploads');

        $newFileRecord = Researcher_files::create([
            'research_title_id' => $id,
            'filename' => $request->file('file')->getClientOriginalName(),
            'filepath' => $path,
            'filetype' => $request->file('file')->getClientOriginalExtension(),
            'category' => $request->category,
            'revision_number' => -1, // -1 means In-Progress Workspace
        ]);

        $researchTitle->files()->attach($newFileRecord->id);

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

        $researchTitle = Research_title::findOrFail($file->research_title_id);
        if (!$user->researcher || $researchTitle->researcher_id !== $user->researcher->id) {
            if (request()->expectsJson())
                return response()->json(['error' => 'Unauthorized'], 403);
            abort(403);
        }

        if ($file->revision_number != -1) {
            if (request()->expectsJson())
                return response()->json(['error' => 'Not a draft document'], 403);
            abort(403, 'Can only delete files from the active draft workspace.');
        }

        Storage::disk('public_uploads')->delete(str_replace('storage/', '', $file->filepath));
        $file->delete();

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

        // Find the recommendation letter file
        $file = researcher_files::where('research_title_id', $id)
            ->where('filetype', 'Result of Review (Admin Generated)')
            ->latest()
            ->first();

        if (!$file || !Storage::disk('public_uploads')->exists(str_replace('storage/', '', $file->filepath))) {
            return back()->with('error', 'Recommendation letter not found.');
        }

        // Serve the file
        // Note: The filepath in DB acts as relative path for public_uploads disk (once prefix stripped if any)
        $storagePath = str_replace('storage/', '', $file->filepath);

        return response()->file(public_path($storagePath));
    }
    public function submitRevisions(Request $request, $id)
    {
        $researchTitle = Research_title::findOrFail($id);
        $user = Auth::user();

        // Security check
        if (!$user->researcher || $researchTitle->researcher_id !== $user->researcher->id) {
            abort(403, 'Unauthorized action.');
        }

        // Validate message if needed (optional)
        $request->validate([
            'revision_message' => 'nullable|string|max:1000',
        ]);

        if (in_array($researchTitle->Status, ['Waiting for Revision', 'Incomplete'])) {

            $isIncomplete = $researchTitle->Status === 'Incomplete';

            // Only enforce the Draft Workspace check for formal Revisions
            if (!$isIncomplete) {
                // Check if any files have been uploaded into the draft workspace
                $hasUpdatedFiles = Researcher_files::where('research_title_id', $id)
                    ->where('revision_number', -1)
                    ->exists();

                if (!$hasUpdatedFiles) {
                    return back()->with('error', 'You must upload at least one document to your Revision Workspace before submitting corrections.');
                }

                // Group all draft workspace files into a formal new Revision Folder
                $currentMax = Researcher_files::where('research_title_id', $id)
                    ->where('revision_number', '>', 0)
                    ->max('revision_number') ?? 0;
                $newRevisionNumber = $currentMax + 1;

                Researcher_files::where('research_title_id', $id)
                    ->where('revision_number', -1)
                    ->update(['revision_number' => $newRevisionNumber]);
            }

            // Determine new status
            $newStatus = $isIncomplete ? 'Incomplete Resubmitted' : 'Revision Submitted';
            $logMessage = "Resubmitted corrections: " . $request->revision_message;

            // Create Submission Feedback (User Correction) & Revision Log for Admin View
            if ($request->revision_message) {
                SubmissionFeedback::create([
                    'research_title_id' => $researchTitle->id,
                    'user_id' => $user->id,
                    'type' => 'user_correction',
                    'message' => $request->revision_message,
                ]);

                \App\Models\RevisionLog::create([
                    'research_title_id' => $researchTitle->id,
                    'user_id' => $user->id,
                    'message' => $request->revision_message,
                ]);
            } else {
                \App\Models\RevisionLog::create([
                    'research_title_id' => $researchTitle->id,
                    'user_id' => $user->id,
                    'message' => $isIncomplete ? 'Resubmitted initial intake files without additional notes.' : 'Resubmitted without additional notes.',
                ]);
            }

            $researchTitle->Status = $newStatus;

            // Reset all assigned reviewers back to Pending so the protocol appears on their dashboard
            if (!$isIncomplete) {
                foreach ($researchTitle->reviewers as $reviewer) {
                    $researchTitle->reviewers()->updateExistingPivot($reviewer->id, ['status' => 'Pending']);
                }
            }

            $researchTitle->save();

            $successMsg = $isIncomplete ? 'Corrections submitted successfully! Document history synced.' : 'Revisions submitted successfully! Document history synced.';

            return redirect()->route('home')->with('success', $successMsg);
        }

        return back()->with('error', 'Unable to submit revisions. Current status: ' . $researchTitle->Status);
    }
}

