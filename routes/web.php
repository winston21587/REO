<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('index');
});


Route::get('/admin', function () {
    return view('admin.dashboard', ['title' => 'Admin']);
});

Route::get('/home', function () {
    return view('home');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:10,1'); //anti hecker (wla brute force)
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// protect routes:
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
});

// Example admin-only group (requires custom middleware - see below)
Route::middleware(['auth','is_admin'])->group(function () {
    Route::get('/admin', function(){ return view('admin.index'); });
});