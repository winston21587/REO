<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CmsContent;
use App\Models\ResearchCategory;
use App\Models\Department;

use App\Models\Program;
use App\Models\College;

class CmsController extends Controller
{
    public function index()
    {
        $contents = CmsContent::all()->pluck('value', 'key');
        return view('admin.cms.pages', compact('contents'));
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
