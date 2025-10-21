<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if($user->role === 'admin'){
                return redirect()->route('admin.analytics');
            }
            if($user->role === 'researcher'){
                return redirect()->route('home');
            }
            return redirect()->intended(route('home'));

            
            
        }

        return back()->withErrors([
            'email' => 'The provided credentials does not match our records.',
        ]);
    }


    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle registration form
    public function register_internal(Request $request)
    {

        $data = $request->validate([
            'FirstName'     => 'required|string|max:255',
            'MiddleName'     => 'nullable|string|max:255',
            'LastName'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed', 
            'college' => 'required|string|max:155',  
            'department' => 'required|string|max:155',   
            'course' => 'required|string|max:155', 
            'contact' => 'nullable|string|max:11',

        ]);

        $verificationCode = rand(100000, 999999);   
        // $verificationCode = 12345678;   

            $user = User::create([
            'first_name'  => $data['FirstName'],
            'middle_name' => $data['MiddleName'] ?? null,
            'last_name' => $data['LastName'],
            'college' => $data['college'] ?? null,
            'department' => $data['department'] ?? null,
            'contact' => $data['contact'] ?? null,
            'course' => $data['course'] ?? null,
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'external_user' =>  false,
            'role'     => 'researcher', // default role
            'verification_code' => $verificationCode,
            'is_verified' => false,
        ]);
    

    // Send email with verification code
        Mail::to($user->email)->send(new \App\Mail\VerifyEmail($user));

        // Auth::login($user);
        // return redirect()->route('home');

        return redirect()->route('verify.show', ['email' => $user->email])->with('success', 'We sent a verification code to your email.');
    }
    public function register_external(Request $request)
    {

        $data = $request->validate([
            'FirstName'     => 'required|string|max:255',
            'MiddleName'     => 'nullable|string|max:255',
            'LastName'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed', 
            'institute' => 'required|string|max:155',  
            'contact' => 'nullable|string|max:11',

        ]);

        $verificationCode = rand(100000, 999999);   
        // $verificationCode = 12345678;   

            $user = User::create([
            'first_name'  => $data['FirstName'],
            'middle_name' => $data['MiddleName'] ?? null,
            'last_name' => $data['LastName'],
            'contact' => $data['contact'] ?? null,
            'email'    => $data['email'],
            'institute' => $data['institute'] ?? null, 
            'password' => Hash::make($data['password']),
            'external_user' =>  false,
            'role'     => 'researcher', // default role
            'verification_code' => $verificationCode,
            'is_verified' => false,
        ]);
    

    // Send email with verification code
        Mail::to($user->email)->send(new \App\Mail\VerifyEmail($user));

        // Auth::login($user);
        // return redirect()->route('home');

        return redirect()->route('verify.show', ['email' => $user->email])->with('success', 'We sent a verification code to your email.');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('index');
    }   

    public function acceptTerms(Request $request)
{
    $user = Auth::user();
    $user->first_time = true;
    $user->save();

    return redirect()->route('instructions')->with('status', 'Thanks for accepting the terms!');
}
public function showVerifyForm(Request $request)
{
        if (!$request->has('email')) {
        return redirect()->route('register')
                         ->with('error', 'Please register first to get your verification code.');
    }
    return view('auth.verification');
}

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|numeric',
        ]);

        $user = User::where('email', $request->email)
                    ->where('verification_code', $request->code)
                    ->first();

        if (!$user) {
            return back()->with('error', 'Invalid verification code.');
        }

        $user->is_verified = true;
        $user->verification_code = null;
        $user->save();

        // Auth::login($user);

        return redirect()->route('login')->with('success', 'Email verified successfully!');
    }


}