<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SettingsController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'institute' => 'nullable|string|max:255',
            'college' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
        ]);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;

        if ($user->external_user) {
            $user->institute = $request->institute;
        } else {
            $user->college = $request->college;
            $user->department = $request->department;
        }

        $user->save();

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
        
        if ($user->delete()) {
             return redirect()->route('login')->with('success', 'Your account has been deleted.');
        }

        return back()->with('error', 'Failed to delete account.');
    }
}
