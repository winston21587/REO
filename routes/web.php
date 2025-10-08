<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
// use App\Http\Controllers\Con;                   make a controller for index page just to logout every time it refreshes

// Route::get('/', function () {
//     return view('index');
// });
// Route::get('/admin', function () {
//     return view('admin.dashboard', ['title' => 'Admin']);
// });
// Route::get('/home', function () {
//     return view('home');
// });
// Route::get('/home', function () { return view('home'); })->name('home');

Route::get('/', function () { return view('index'); })->name('index');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login'); //anti hecker (wla brute force)
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register');

    Route::get('/verify', [AuthController::class, 'showVerifyForm'])->name('verify.show'); 
    Route::post('/verify', [AuthController::class, 'verifyCode'])->name('verify.submit'); //OTP func
}); 

Route::get('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// exlusive for users only or something (middleware gaming)
Route::middleware(['auth', 'role:researcher'])->group(function () {
    Route::get('/home', function () { return view('home'); })->name('home');    
    Route::get('/resources', function () { return view('resources'); })->name('resources');            //controller for these pages will be implemented soon
    Route::get('/instructions', function () { return view('instructions'); })->name('instructions');
    Route::get('/forms', function () { return view('forms.submission'); });
    Route::get('/forms/submission', function () { return view('forms.submission'); })->name('submission');
});


// Route::get('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// admin access prolly
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function(){ return view('admin.dashboard'); })->name('admin.dashboard');

});
// Route::middleware(['auth','is_admin'])->group(function () {
//     Route::get('/admin', function(){ return view('admin.index'); });
// });

Route::post('/accept-terms', [AuthController::class, 'acceptTerms'])->name('accept.terms');






// bruh the auth is actually in the:
// protected $routeMiddleware = [
//     'auth' => \App\Http\Middleware\Authenticate::class,
//     'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
//     // ...
// ];
// lmao