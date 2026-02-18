<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequirement;
use Illuminate\Http\Request;

class DocumentRequirementController extends Controller
{
    public function index()
    {
        $documents = DocumentRequirement::orderBy('created_at', 'desc')->get();
        return view('admin.manage_documents', compact('documents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_type' => 'required|string|max:50',
            'is_required' => 'boolean',
            'is_multiple' => 'boolean',
        ]);

        // Checkbox handling: if unchecked, it won't be in request, so default to false
        $validated['is_required'] = $request->has('is_required');
        $validated['is_multiple'] = $request->has('is_multiple');

        DocumentRequirement::create($validated);

        return redirect()->route('admin.manage_documents')->with('success', 'Document requirement created successfully.');
    }

    public function update(Request $request, $id)
    {
        $document = DocumentRequirement::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_type' => 'required|string|max:50',
            'is_required' => 'boolean',
            'is_multiple' => 'boolean',
        ]);

        $validated['is_required'] = $request->has('is_required');
        $validated['is_multiple'] = $request->has('is_multiple');

        $document->update($validated);

        return redirect()->route('admin.manage_documents')->with('success', 'Document requirement updated successfully.');
    }

    public function destroy($id)
    {
        DocumentRequirement::findOrFail($id)->delete();
        return redirect()->route('admin.manage_documents')->with('success', 'Document requirement deleted successfully.');
    }
}
