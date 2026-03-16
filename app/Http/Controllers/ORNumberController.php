<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Research_title;
use App\Models\TitleLog;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;

class ORNumberController extends Controller
{
    public function researcherSubmitOR(Request $request, $id)
    {
        $request->validate([
            'or_number' => 'required|string|max:255',
            'or_file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:20480', // Max 20MB
        ]);

        $title = Research_title::findOrFail($id);
        
        // Handle physical file upload
        if ($request->hasFile('or_file')) {
            $file = $request->file('or_file');
            $filename = time() . '_OR_' . $request->or_number . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads/official_receipts', $filename, 'public_uploads');
            $title->or_file_path = $path;
        }

        // Save the OR Number and reset verification just in case it's a re-upload
        $title->Official_Receipt_Number = $request->or_number;
        $title->is_or_verified = false;
        $title->save();

        // Create Activity Log
        TitleLog::create([
            'research_title_id' => $title->id,
            'user_id' => Auth::id(),
            'action' => 'Official Receipt Uploaded',
            'description' => "Uploaded Official Receipt #{$request->or_number} pending Admin verification.",
        ]);

        return redirect()->back()->with('success', 'Official Receipt submitted and is pending verification.');
    }

    public function verifyOR(Request $request, $id)
    {
        $title = Research_title::findOrFail($id);
        
        $title->is_or_verified = true;
        $title->save();

        TitleLog::create([
            'research_title_id' => $title->id,
            'user_id' => Auth::id(),
            'action' => 'Official Receipt Verified',
            'description' => "Admin officially verified Receipt #{$title->Official_Receipt_Number}.",
        ]);

        return redirect()->back()->with('success', 'Official Receipt has been verified.');
    }

    public function rejectOR(Request $request, $id)
    {
        $title = Research_title::findOrFail($id);
        
        $oldOr = $title->Official_Receipt_Number;

        // Optionally delete the physical file to save space
        if ($title->or_file_path) {
            $physicalPath = public_path($title->or_file_path);
            if (file_exists($physicalPath)) {
                unlink($physicalPath);
            }
        }

        // Reset the columns so the researcher can submit again
        $title->Official_Receipt_Number = null;
        $title->or_file_path = null;
        $title->is_or_verified = false;
        $title->save();

        TitleLog::create([
            'research_title_id' => $title->id,
            'user_id' => Auth::id(),
            'action' => 'Official Receipt Rejected',
            'description' => "Admin rejected the submitted receipt #{$oldOr}. A new receipt must be uploaded.",
        ]);

        return redirect()->back()->with('error', 'Official Receipt was rejected.');
    }
}
