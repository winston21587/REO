<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\College;

class SettingsController extends Controller
{
    public function index()
    {
        $colleges = College::with('departments.programs')->get();
        return view('user_settings', compact('colleges'));
    }

    public function updateProfile(Request $request)
{
    $user = Auth::user();
    
    $request->validate([
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'institute' => 'nullable|string|max:255',
        'college' => 'nullable|string|max:255',
        'department' => 'nullable|string|max:255',
        'program' => 'nullable|string|max:255',
    ]);

    $user->update([
        'first_name' => $request->first_name,
        'middle_name' => $request->middle_name,
        'last_name' => $request->last_name,
    ]);

    if ($user->researcher) {
        $researcherData = [];

        if ($user->researcher->external_user) {
            $researcherData['institute'] = $request->institute;
        } else {
            $researcherData['college'] = $request->college;
            $researcherData['department'] = $request->department;
            $researcherData['program'] = $request->program;
        }

        $user->researcher->update($researcherData);
    }

    return back()->with('success', 'Profile updated successfully.');
}


    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
        }

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()->withErrors(['password' => 'Incorrect password. Account deletion cancelled.']);
        }

        $user = Auth::user();
        Auth::logout();
        
        // Append a timestamp to the email to free up the address for re-registration
        $user->email = $user->email . '.deleted.' . time();
        $user->save();

        if ($user->researcher) {
            foreach ($user->researcher->researchTitles as $title) {
                $title->Status = 'Terminated';
                $title->save(); // Save individually to fire TitleLog Model events
            }
        }

        if ($user->delete()) {
             return redirect()->route('login')->with('success', 'Your account has been deleted.');
        }

        return back()->with('error', 'Failed to delete account.');
    }

    public function updateEmailPreferences(Request $request)
    {
        $user = Auth::user();
        
        // Validate input (expecting booleans or "on" from checkboxes)
        $preferences = [
            'submission_status' => $request->has('submission_status'),
            'appointment_reminders' => $request->has('appointment_reminders'),
            'new_resources' => $request->has('new_resources'),
        ];

        $user->email_preferences = $preferences;
        $user->save();

        return back()->with('success', 'Email preferences updated successfully.');
    }

    public function updateDisplayPreferences(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'theme' => 'required|in:light,dark',
            'density' => 'required|in:comfortable,compact',
        ]);

        $preferences = [
            'theme' => $request->theme,
            'density' => $request->density,
        ];

        $user->display_preferences = $preferences;
        $user->save();

        return back()->with('success', 'Display settings updated successfully.');
    }
}
