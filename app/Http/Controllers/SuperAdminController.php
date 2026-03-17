<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\College;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    public function manageAdmins(Request $request)
    {
        $query = User::with('admin')->where('role', 'admin');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('college')) {
            $query->whereHas('admin', function($q) use ($request) {
                $q->where('college', $request->college);
            });
        }

        if ($request->filled('status')) {
            $query->whereHas('admin', function($q) use ($request) {
                if ($request->status === 'internal') {
                    $q->where('external_user', false);
                } elseif ($request->status === 'external') {
                    $q->where('external_user', true);
                }
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        $colleges = College::all();

        return view('super_admin.manage_admins', compact('users', 'colleges'));
    }

    public function createAdmin(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password), 
                'role' => 'admin',
                'is_verified' => true,
            ]);
            
            $user->email_verified_at = now();
            $user->save();

            Admin::create([
                'user_id' => $user->id,
            ]);

            DB::commit();

            // Send Email
            try {
                \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\AdminCreatedMail($user, $request->password));
                $emailStatus = ' An email with their credentials has been sent.';
            } catch (\Exception $e) {
                // Ignore email failure for the main transaction, but notify user
                $emailStatus = ' However, the credential email failed to send to ' . $user->email . '.';
            }

            return redirect()->back()->with('success', 'Admin created successfully.' . $emailStatus);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating admin: ' . $e->getMessage());
        }
    }

    public function toggleAdminStatus(User $user)
    {
        if ($user->role !== 'admin') {
            return redirect()->back()->with('error', 'You can only toggle the status of admins.');
        }

        if ($user->email_verified_at) {
            $user->email_verified_at = null;
            $message = 'Admin account deactivated successfully.';
        } else {
            $user->email_verified_at = now();
            $message = 'Admin account activated successfully.';
        }
        
        $user->save();

        return redirect()->back()->with('success', $message);
    }

    public function deleteAdmin(User $user)
    {
        if ($user->role !== 'admin') {
            return redirect()->back()->with('error', 'You can only delete admin accounts from this page.');
        }

        try {
            $user->delete();
            return redirect()->back()->with('success', 'Admin deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting admin: ' . $e->getMessage());
        }
    }

    public function manageReviewers(Request $request)
    {
        $query = User::with('reviewer')->where('role', 'reviewer');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('college')) {
            $query->whereHas('reviewer', function($q) use ($request) {
                $q->where('college', $request->college);
            });
        }

        if ($request->filled('status')) {
            $query->whereHas('reviewer', function($q) use ($request) {
                if ($request->status === 'internal') {
                    $q->where('external_user', false);
                } elseif ($request->status === 'external') {
                    $q->where('external_user', true);
                }
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        $colleges = College::with('departments.programs')->get();

        return view('super_admin.manage_reviewers', compact('users', 'colleges'));
    }

    public function createReviewer(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'college' => 'nullable|string|max:255',
            'expertise' => 'nullable|string|max:1000',
            'training_completed' => 'nullable|boolean',
            'external_user' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $generatedPassword = \Illuminate\Support\Str::random(12);

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($generatedPassword), 
                'role' => 'reviewer',
                'is_verified' => true,
            ]);
            
            $user->email_verified_at = now();
            $user->save();
            
            $expertiseArray = null;
            if ($request->filled('expertise')) {
                $expertiseArray = array_map('trim', explode(',', $request->expertise));
            }

            \App\Models\Reviewer::create([
                'user_id' => $user->id,
                'college' => $request->college,
                'expertise' => $expertiseArray,
                'training_completed' => $request->boolean('training_completed', false),
                'external_user' => $request->boolean('external_user', false),
            ]);

            DB::commit();

            // Send Email
            try {
                \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\ReviewerCreatedMail($user)); 
                $emailStatus = ' An email has been sent to the reviewer.';
            } catch (\Exception $e) {
                // Ignore email failure for the main transaction, but notify user
                $emailStatus = ' However, the credential email failed to send to ' . $user->email . '.';
            }

            return redirect()->back()->with('success', 'Reviewer created successfully.' . $emailStatus);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating reviewer: ' . $e->getMessage());
        }
    }

    public function toggleReviewerStatus(User $user)
    {
        if ($user->role !== 'reviewer') {
            return redirect()->back()->with('error', 'You can only toggle the status of reviewers.');
        }

        if ($user->email_verified_at) {
            $user->email_verified_at = null;
            $message = 'Reviewer account deactivated successfully.';
        } else {
            $user->email_verified_at = now();
            $message = 'Reviewer account activated successfully.';
        }
        
        $user->save();

        return redirect()->back()->with('success', $message);
    }

    public function deleteReviewer(User $user)
    {
        if ($user->role !== 'reviewer') {
            return redirect()->back()->with('error', 'You can only delete reviewer accounts from this page.');
        }

        try {
            $user->delete();
            return redirect()->back()->with('success', 'Reviewer deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting reviewer: ' . $e->getMessage());
        }
    }
}
