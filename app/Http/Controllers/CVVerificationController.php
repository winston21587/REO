<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Research_title;
use App\Models\TitleLog;
use App\Models\UserNotification;
use App\Models\ResearchCategory;
use Illuminate\Support\Facades\Auth;

class CVVerificationController extends Controller
{
    /**
     * Admin marks the CV as Valid (researcher classification is confirmed).
     */
    public function verifyCv(Request $request, $id)
    {
        $title = Research_title::findOrFail($id);

        $title->is_cv_verified = true;
        $title->cv_verification_status = 'Valid';
        $title->cv_rejection_remarks = null;
        $title->save();

        TitleLog::create([
            'research_title_id' => $title->id,
            'user_id'           => Auth::id(),
            'action'            => 'CV Classification Verified',
            'description'       => "Admin verified that the researcher's CV matches the selected project type: {$title->project_type}.",
        ]);

        return redirect()->back()->with('success', 'CV Classification has been verified successfully.');
    }

    /**
     * Admin marks the CV as Invalid — sets status to Incomplete and notifies researcher.
     */
    public function invalidateCv(Request $request, $id)
    {
        $request->validate([
            'cv_remarks' => 'required|string|max:1000',
        ]);

        $title = Research_title::findOrFail($id);

        $title->is_cv_verified = false;
        $title->cv_verification_status = 'Invalid';
        $title->cv_rejection_remarks = $request->cv_remarks;
        $title->Status = 'Incomplete';
        $title->save();

        TitleLog::create([
            'research_title_id' => $title->id,
            'user_id'           => Auth::id(),
            'action'            => 'CV Classification Invalid',
            'description'       => "Admin flagged a classification mismatch. Submission returned to Incomplete. Remarks: {$request->cv_remarks}",
        ]);

        // Notify researcher
        $researcher = $title->researcher;
        if ($researcher) {
            UserNotification::create([
                'user_id'     => $researcher->user_id,
                'research_id' => $title->id,
                'title'       => 'CV Classification Mismatch',
                'message'     => "Your submission \"{$title->Study_Protocol_title}\" has been flagged for an incorrect project type. Please log in and correct your classification.\n\nReason: {$request->cv_remarks}",
                'type'        => 'warning',
                'is_read'     => false,
            ]);
        }

        return redirect()->back()->with('error', 'CV has been marked as Invalid and the researcher has been notified.');
    }

    /**
     * Researcher corrects their project type after an invalid CV decision.
     */
    public function researcherCorrectProjectType(Request $request, $id)
    {
        $title = Research_title::findOrFail($id);

        // Only allow correction if CV was flagged as Invalid
        if ($title->cv_verification_status !== 'Invalid') {
            return redirect()->back()->with('error', 'Correction is not allowed at this stage.');
        }

        $request->validate([
            'project_type' => 'required|in:Funded Research,Course Requirement',
            'sub_type'     => 'required|string',
            'Adviser'      => 'required_if:project_type,Course Requirement|nullable|string|max:255',
        ]);

        // Derive funding_type or course_type from sub_type
        $fundedCategories   = ResearchCategory::where('classification', 'Funded Research')->pluck('name')->toArray();
        $courseCategories   = ResearchCategory::where('classification', 'Course Requirement')->pluck('name')->toArray();

        $title->project_type = $request->project_type;
        $title->Research_Category = $request->sub_type;

        if ($request->project_type === 'Funded Research') {
            $title->funding_type = $request->sub_type;
            $title->course_type  = null;
            $title->Adviser      = null;
        } else {
            $title->course_type  = $request->sub_type;
            $title->funding_type = null;
            $title->Adviser      = $request->Adviser;
        }

        // Reset CV verification so admin can re-verify
        $title->cv_verification_status = null;
        $title->is_cv_verified = false;
        $title->cv_rejection_remarks = null;
        $title->save();

        TitleLog::create([
            'research_title_id' => $title->id,
            'user_id'           => Auth::id(),
            'action'            => 'Project Type Corrected',
            'description'       => "Researcher corrected project type to: {$request->project_type} — {$request->sub_type}. Awaiting re-verification.",
        ]);

        return redirect()->back()->with('success', 'Your project type has been updated. The admin will re-verify your CV classification.');
    }
}
