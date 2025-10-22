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
        'Adviser' => 'required|string|max:255',

        // PDF uploads
        'files.application_form' => 'required|file|mimes:pdf|max:20480',
        'files.research_protocol' => 'required|file|mimes:pdf|max:20480',
        'files.technical_clearance' => 'required|file|mimes:pdf|max:20480',
        'files.data_collection_instruments' => 'required|file|mimes:pdf|max:20480',
        'files.informed_consent' => 'required|file|mimes:pdf|max:20480',
        'files.curriculum_vitae' => 'required|file|mimes:pdf|max:20480',

        // Word document uploads
        'files.study_protocol_form' => 'required|file|mimes:doc,docx|max:20480',
        'files.informed_consent_form' => 'required|file|mimes:doc,docx|max:20480',
        'files.exempt_review_form' => 'required|file|mimes:doc,docx|max:20480',

        // Optional supplementary files
        'files.supplementary_docs.*' => 'nullable|file|mimes:pdf|max:20480',
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

    // ✅ Create research title
    $research = Research_title::create([
        'Study_Protocol_title' => $validated['Study_Protocol_title'],
        'Research_Category' => $validated['Research_Category'],
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
    $researchTitles = Research_title::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

    return view('home', compact('researchTitles'));
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

        return redirect()->back()->with('success', 'File updated successfully!');
    }
}


// INSERT INTO researcher_files ( filename, filepath,filetype, created_at, updated_at) VALUES ( 'sample.pdf', 'research_files/sample.pdf','protocol form', NOW(), NOW());