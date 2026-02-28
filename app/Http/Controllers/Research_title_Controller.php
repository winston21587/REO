<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Research_title;
use App\Models\researcher_files;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\DocumentRequirement;
use App\Models\SubmissionFeedback;

class Research_title_Controller extends Controller
{
    public function showSubmit()
    {
        $requirements = DocumentRequirement::all();
        return view('submit', compact('requirements'));
    }






    public function submitTitle(Request $request)
    {
        $user = Auth::user();

        // 1. Base Validation
        $rules = [
            'Study_Protocol_title' => 'required|string|max:255',
            'Research_Category' => 'required|string|max:255',
            'other_category' => 'nullable|string|max:255',
            'Adviser' => 'required|string|max:255',
        ];

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
                if ($type === 'PDF') $mimes[] = 'pdf';
                if ($type === 'Word') array_push($mimes, 'doc', 'docx');
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

        // ✅ Create research title
        $research = Research_title::create([
            'Study_Protocol_title' => $validated['Study_Protocol_title'],
            'Research_Category' => $finalCategory,
            'Created_by' => $user->first_name . ' ' . $user->last_name,
            'researcher_id' => $user->researcher->id,
            'Official_Receipt_Number' => '011',
            'Adviser' => $validated['Adviser'],
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
        $researchTitle = Research_title::with(['files', 'adminFiles', 'titleLogs.user'])->findOrFail($id);
        return view('researcher_files', compact('researchTitle'));
    }

    public function updateFile(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB limit
            'file_id' => 'required|integer',
        ]);

        $researchFile = Researcher_files::findOrFail($request->file_id);

        // Delete old file from storage
        if (Storage::exists($researchFile->filepath)) {
            Storage::delete($researchFile->filepath);
        }

        // Store new file
        $path = $request->file('file')->store('uploads/research_files', 'public_uploads');

        // Update record
        $researchFile->update([
            'filename' => $request->file('file')->getClientOriginalName(),
            'filepath' => $path,
            'filetype' => $request->file('file')->getClientOriginalExtension(),
        ]);



        return redirect()->back()->with('success', 'File updated successfully!');
        return redirect()->back()->with('success', 'File updated successfully!');
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

            // Check if any files have been updated since the status was set (i.e., since the title was last updated)
            // Logic: If user updated a file, file->updated_at should be > title->updated_at
            // Fix: Specify table name to avoid ambiguity with pivot table columns
            $hasUpdatedFiles = $researchTitle->files()->where('researcher_files.updated_at', '>', $researchTitle->updated_at)->exists();

            if (!$hasUpdatedFiles) {
                return back()->with('error', 'You must update at least one document before submitting corrections.');
            }

            // Determine new status
            // Changed from 'Pending'/'Revision Submitted' to 'Corrections Submitted' to distinguish in Admin Dashboard
            $newStatus = 'Corrections Submitted';
            $logMessage = "Resubmitted corrections: " . $request->revision_message;

            // Create Submission Feedback (User Correction)
            if ($request->revision_message) {
                SubmissionFeedback::create([
                    'research_title_id' => $researchTitle->id,
                    'user_id' => $user->id,
                    'type' => 'user_correction',
                    'message' => $request->revision_message,
                ]);
            }

            $researchTitle->Status = $newStatus;
            $researchTitle->save();

            $successMsg = ($newStatus === 'Pending') ? 'Corrections submitted successfully! Application is now Pending review.' : 'Revisions submitted successfully! Status updated.';

            return redirect()->route('home')->with('success', $successMsg);
        }

        return back()->with('error', 'Unable to submit revisions. Current status: ' . $researchTitle->Status);
    }
}

