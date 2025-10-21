<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Research_title;
use App\Models\researcher_files;
use Illuminate\Support\Facades\Auth;  // need this for the auth::user

class Research_title_Controller extends Controller
{
      public function showSubmit()
    {
        return view('submit');
    }
    public function fetchFiles($id)
{
    $files = researcher_files::where('id', function($query) use ($id) {
        $query->select('researcher_file_id')
              ->from('research_titles')
              ->where('id', $id)
              ->limit(1);
    })->get();

    return response()->json($files);
}


    public function submitTitle(Request $request)
    {
        // dd($request->all());
        // Validate the incoming request data
        $data = $request->validate([
            'Study_Protocol_title' => 'required|string|max:255',
            'Research_Category' => 'required|string|max:255',
            'Adviser' => 'required|string|max:255',
            'research_files.*' => 'nullable|file|mimes:pdf,doc,docx|max:20480', // max 20MB per file
        ]);
        $user = Auth::user();
        $uploadedFileIds = [];

    // ✅ Handle file upload
    if ($request->hasFile('research_files')) {
        foreach ($request->file('research_files') as $file) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('research_files', $filename, 'public');

            $researchFile = researcher_files::create([
                'filename' => $filename,
                'filepath' => 'storage/' . $path,
                'filetype' => $file->getClientOriginalExtension(),
            ]);

            $uploadedFileIds[] = $researchFile->id;
        }
    }

        // Save research title info
        $research = Research_title::create([
            'Study_Protocol_title' => $data['Study_Protocol_title'],
            'Research_Category' => $data['Research_Category'],
            'Created_by' => $user->name, // user’s name
            'user_id' => $user->id,      // user’s id
            'Official_Receipt_Number' => 011,
            'Adviser' => $data['Adviser'],
            'researcher_file_id' => !empty($uploadedFileIds) ? $uploadedFileIds[0] : null,   // adjust this based on your logic
        ]);

        // Redirect or return a response
        return redirect(route('home'))->with('success', 'Research title successfully added!');
    }

    public function showTitles()
{
    $user = Auth::user();
    $researchTitles = Research_title::where('user_id', $user->id)->get();

    return view('home', compact('researchTitles'));
}
}
// INSERT INTO researcher_files ( filename, filepath,filetype, created_at, updated_at) VALUES ( 'sample.pdf', 'research_files/sample.pdf','protocol form', NOW(), NOW());