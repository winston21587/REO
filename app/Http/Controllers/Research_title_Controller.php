<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Research_title;
use App\Models\researcher_files;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Research_title_Controller extends Controller
{
    public function showSubmit()
    {
        return view('submit');
    }






    public function submitTitle(Request $request)
    {
        $user = Auth::user();

        // ✅ Validation
        $validated = $request->validate([
            'Study_Protocol_title' => 'required|string|max:255',
            'Research_Category' => 'required|string|max:255',
            'other_category' => 'nullable|string|max:255',
            'Adviser' => 'required|string|max:255',

            // PDF uploads
            'files.application_form' => 'required|file|mimes:pdf|max:25600',
            'files.research_protocol' => 'required|file|mimes:pdf|max:25600',
            'files.technical_clearance' => 'required|file|mimes:pdf|max:25600',
            'files.data_collection_instruments' => 'required|file|mimes:pdf|max:25600',
            'files.informed_consent' => 'required|file|mimes:pdf|max:25600',
            'files.curriculum_vitae' => 'required|file|mimes:pdf|max:25600',

            // Word document uploads
            'files.study_protocol_form' => 'required|file|mimes:doc,docx|max:25600',
            'files.informed_consent_form' => 'required|file|mimes:doc,docx|max:25600',
            'files.exempt_review_form' => 'required|file|mimes:doc,docx|max:25600',

            // Optional supplementary files
            'files.supplementary_docs.*' => 'nullable|file|mimes:pdf|max:25600',
        ]);

        // ✅ Define document types for looping
        $fileFields = [
            'application_form',
            'research_protocol',
            'technical_clearance',
            'data_collection_instruments',
            'informed_consent',
            'curriculum_vitae',
            'study_protocol_form',
            'informed_consent_form',
            'exempt_review_form',
        ];

        $uploadedFileIds = [];

        // ✅ Store main documents
        foreach ($fileFields as $field) {
            if ($request->hasFile("files.$field")) {
                $file = $request->file("files.$field");
                $filename = time() . '_' . $field . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('uploads/research_files', $filename, 'public_uploads');

                $fileRecord = researcher_files::create([
                    'filename' => $filename,
                    'filepath' => $path, // No storage/ prefix needing removal later if we stick to this
                    'filetype' => $file->getClientOriginalExtension(),
                    'category' => $field,
                ]);

                $uploadedFileIds[] = $fileRecord->id;
            }
        }

        // ✅ Handle supplementary files
        if ($request->hasFile('files.supplementary_docs')) {
            foreach ($request->file('files.supplementary_docs') as $file) {
                $filename = time() . '_supplementary_' . $file->getClientOriginalName();
                $path = $file->storeAs('uploads/research_files', $filename, 'public_uploads');

                $fileRecord = researcher_files::create([
                    'filename' => $filename,
                    'filepath' => $path,
                    'filetype' => $file->getClientOriginalExtension(),
                    'category' => 'supplementary_docs',
                ]);

                $uploadedFileIds[] = $fileRecord->id;
            }
        }

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
        $titles = Research_title::with('files')
            ->where('researcher_id', $user->researcher->id)
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('home', compact('titles'));
    }

    // Show all files for a specific research title
    public function manageFiles($id)
    {
        $researchTitle = Research_title::with(['files', 'adminFiles'])->findOrFail($id);
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

        if ($researchTitle->Status === 'Waiting for Revision') {
            
            // Create Revision Log
            \App\Models\RevisionLog::create([
                'research_title_id' => $researchTitle->id,
                'user_id' => $user->id,
                'message' => $request->revision_message,
            ]);

            $researchTitle->Status = 'Revision Submitted';
            $researchTitle->save();

            return redirect()->route('home')->with('success', 'Revisions submitted successfully! status updated.');
        }

        return back()->with('error', 'Unable to submit revisions. Current status: ' . $researchTitle->Status);
    }
}

