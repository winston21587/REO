<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Research_title;
use App\Models\researcher_files;
use Illuminate\Support\Facades\Auth;  // need this for the auth::user
use Illuminate\Support\Facades\Storage;

class Research_title_Controller extends Controller
{
      public function showSubmit()
    {
        return view('submit');
    }
//     public function fetchFiles($id)
// {
//     $files = researcher_files::where('id', function($query) use ($id) {
//         $query->select('researcher_file_id')
//               ->from('research_titles')
//               ->where('id', $id)
//               ->limit(1);
//     })->get();

//     return response()->json($files);
// }





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
            $path = $file->storeAs('research_files', $filename, 'public');

            $fileRecord = researcher_files::create([
                'filename' => $filename,
                'filepath' => 'storage/' . $path,
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
            $path = $file->storeAs('research_files', $filename, 'public');

            $fileRecord = researcher_files::create([
                'filename' => $filename,
                'filepath' => 'storage/' . $path,
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
        'Created_by' => $user->name,
        'user_id' => $user->id,
        'Official_Receipt_Number' => 011,
        'Adviser' => $validated['Adviser'],
    ]);

    // ✅ Attach files to pivot table
    $research->files()->attach($uploadedFileIds);

    return redirect(route('home'))->with('success', 'Research title and all required documents successfully submitted!');
}



    public function showTitles()
    {
        $user = Auth::user();
        $titles = Research_title::with('files')->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(9);

        return view('home', compact('titles'));
    }

    // Show all files for a specific research title
    public function manageFiles($id)
    {
        $researchTitle = Research_title::with('files')->findOrFail($id);
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
        $path = $request->file('file')->store('research_files', 'public');

        // Update record
        $researchFile->update([
            'filename' => $request->file('file')->getClientOriginalName(),
            'filepath' => 'storage/' . $path,
            'filetype' => $request->file('file')->getClientOriginalExtension(),
        ]);

        // Status update removed to allow manual submission
        // $researchTitle = Research_title::find($id);
        // if ($researchTitle && $researchTitle->Status === 'Waiting for Revision') {
        //     $researchTitle->Status = 'Revision Submitted';
        //     $researchTitle->save();
        // }

        return redirect()->back()->with('success', 'File updated successfully!');
    }

    public function viewRecommendationLetter($id)
    {
        $researchTitle = Research_title::findOrFail($id);

        // Security: Ensure user owns the title
        if ($researchTitle->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Find the recommendation letter file
        $file = researcher_files::where('research_title_id', $id)
            ->where('filetype', 'Result of Review (Admin Generated)')
            ->latest()
            ->first();

        if (!$file || !Storage::disk('public')->exists(str_replace('storage/', '', $file->filepath))) {
            return back()->with('error', 'Recommendation letter not found.');
        }

        // Serve the file
        // Note: The filepath in DB is 'storage/uploads/...', but Storage::disk('public') expects 'uploads/...'
        // We need to strip 'storage/' prefix if it exists.
        $storagePath = str_replace('storage/', '', $file->filepath);
        
        return response()->file(storage_path('app/public/' . $storagePath));
    }
    public function submitRevisions($id)
    {
        $researchTitle = Research_title::findOrFail($id);

        if ($researchTitle->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($researchTitle->Status === 'Waiting for Revision') {
            $researchTitle->Status = 'Revision Submitted';
            $researchTitle->save();
            
            // Notify Admin (Optional but good practice)
            // UserNotification::create([...]); 

            return redirect()->route('home')->with('success', 'Revisions submitted successfully! The status has been updated.');
        }

        return back()->with('error', 'Unable to submit revisions. Current status: ' . $researchTitle->Status);
    }
}


// INSERT INTO researcher_files ( filename, filepath,filetype, created_at, updated_at) VALUES ( 'sample.pdf', 'research_files/sample.pdf','protocol form', NOW(), NOW());