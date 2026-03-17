<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CmsContent;
use App\Models\DownloadableResource;
use App\Models\ResearchCategory;
use App\Models\Department;

use App\Models\Program;
use App\Models\College;

class CmsController extends Controller
{
    public function index()
    {
        $contents = CmsContent::all()->pluck('value', 'key');
        $downloadables = DownloadableResource::all();
        return view('admin.cms.pages', compact('contents', 'downloadables'));
    }

    public function content()
    {
        $contents = CmsContent::all()->pluck('value', 'key');
        return view('admin.cms.content', compact('contents'));
    }

    public function updateContent(Request $request)
    {
        $data = $request->except(['_token', 'section']);
        $section = $request->input('section', 'homepage');
        
        foreach ($data as $key => $value) {
            if ($request->hasFile($key)) {
                $path = $request->file($key)->store('uploads/cms', 'public_uploads');
                CmsContent::updateOrCreate(
                    ['key' => $key],
                    ['value' => $path, 'type' => 'image', 'section' => $section]
                );
            } else {
                CmsContent::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value, 'type' => 'text', 'section' => $section]
                );
            }
        }

        return back()->with('success', 'Content updated successfully.');
    }

    public function storeDownloadable(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'file' => 'required|file|max:25600', // max 25MB
            'is_mandatory' => 'nullable|boolean',
        ]);

        $file = $request->file('file');
        
        // Store in storage/app/public/downloads (accessible via symlink)
        // Alternatively, store in public/uploads/downloads to match other cms uploads
        $path = $file->store('uploads/downloads', 'public_uploads');
        
        // Get file size in KB/MB
        $bytes = $file->getSize();
        $size =  $bytes >= 1048576 
                 ? number_format($bytes / 1048576, 2) . ' MB' 
                 : number_format($bytes / 1024, 0) . ' KB';
                 
        $extension = strtoupper($file->getClientOriginalExtension());

        if ($extension === 'DOC' || $extension === 'DOCX') {
            $extension = 'DOCX';
        }

        DownloadableResource::create([
            'title' => $request->title,
            'code' => $request->code,
            'description' => $request->description,
            'file_path' => $path,
            'file_size' => $size,
            'file_extension' => $extension,
            'is_mandatory' => $request->has('is_mandatory'),
        ]);

        return back()->with('success', 'Downloadable resource added successfully.');
    }

    public function updateDownloadable(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'file' => 'nullable|file|max:25600',
        ]);

        $resource = DownloadableResource::findOrFail($id);
        
        $data = [
            'title' => $request->title,
            'code' => $request->code,
            'description' => $request->description,
            'is_mandatory' => $request->has('is_mandatory'),
        ];

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($resource->file_path && file_exists(public_path($resource->file_path))) {
                unlink(public_path($resource->file_path));
            }
            
            $file = $request->file('file');
            $path = $file->store('uploads/downloads', 'public_uploads');
            
            $bytes = $file->getSize();
            $data['file_size'] =  $bytes >= 1048576 
                     ? number_format($bytes / 1048576, 2) . ' MB' 
                     : number_format($bytes / 1024, 0) . ' KB';
                     
            $extension = strtoupper($file->getClientOriginalExtension());
            if ($extension === 'DOC' || $extension === 'DOCX') {
                 $extension = 'DOCX';
            }
            
            $data['file_path'] = $path;
            $data['file_extension'] = $extension;
        }

        $resource->update($data);

        return back()->with('success', 'Downloadable resource updated successfully.');
    }

    public function destroyDownloadable($id)
    {
        $resource = DownloadableResource::findOrFail($id);
        if ($resource->file_path && file_exists(public_path($resource->file_path))) {
            unlink(public_path($resource->file_path));
        }
        $resource->delete();
        return back()->with('success', 'Downloadable resource deleted successfully.');
    }

    public function categories()
    {
        $categories = ResearchCategory::all();
        return view('admin.cms.categories', compact('categories'));
    }


    // Category Routes unnecessary for now
    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        ResearchCategory::create($request->all());
        return back()->with('success', 'Category created successfully.');
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $category = ResearchCategory::findOrFail($id);
        $category->update($request->all());
        return back()->with('success', 'Category updated successfully.');
    }

    public function destroyCategory($id)
    {
        ResearchCategory::findOrFail($id)->delete();
        return back()->with('success', 'Category deleted successfully.');
    }



    public function departments()
    {
        $colleges = College::with('departments.programs')->get();
        return view('admin.cms.departments', compact('colleges'));
    }

    // --- Colleges ---
    public function storeCollege(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255', 'code' => 'required|string|max:10|unique:colleges', 'color_assign' => 'nullable|string']);
        College::create($request->all());
        return back()->with('success', 'College created successfully.');
    }

    public function updateCollege(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255', 'code' => 'required|string|max:10|unique:colleges,code,' . $id]);
        $college = College::findOrFail($id);
        $college->update($request->all());
        return back()->with('success', 'College updated successfully.');
    }

    public function destroyCollege($id)
    {
        College::findOrFail($id)->delete();
        return back()->with('success', 'College deleted successfully.');
    }

    // --- Departments ---
    public function storeDepartment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255', 
            'code' => 'required|string|max:10|unique:departments',
            'college_id' => 'required|exists:colleges,id'
        ]);
        Department::create($request->all());
        return back()->with('success', 'Department created successfully.');
    }

    public function updateDepartment(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255', 
            'code' => 'required|string|max:10|unique:departments,code,' . $id,
            'college_id' => 'required|exists:colleges,id'
        ]);
        Department::findOrFail($id)->update($request->all());
        return back()->with('success', 'Department updated successfully.');
    }

    public function destroyDepartment($id)
    {
        Department::findOrFail($id)->delete();
        return back()->with('success', 'Department deleted successfully.');
    }

    public function storeProgram(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255', 'department_id' => 'required|exists:departments,id']);
        Program::create($request->all());
        return back()->with('success', 'Program created successfully.');
    }

    public function updateProgram(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $program = Program::findOrFail($id);
        $program->update($request->all());
        return back()->with('success', 'Program updated successfully.');
    }

    public function destroyProgram($id)
    {
        Program::findOrFail($id)->delete();
        return back()->with('success', 'Program deleted successfully.');
    }
}
