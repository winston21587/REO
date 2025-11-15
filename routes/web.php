<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\admin;
use App\Http\Controllers\Research_title_Controller;
use App\Http\Controllers\AiCheckController;

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
    Route::post('register/internal', [AuthController::class, 'register_internal'])->name('register.internal');
    Route::post('register/external', [AuthController::class, 'register_external'])->name('register.external');

    Route::get('/verify', [AuthController::class, 'showVerifyForm'])->name('verify.show'); 
    Route::post('/verify', [AuthController::class, 'verifyCode'])->name('verify.submit'); //OTP func
}); 

Route::get('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// exlusive for users only or something (middleware gaming)
Route::middleware(['auth', 'role:researcher'])->group(function () {
    Route::get('/home', [Research_title_Controller::class, 'showTitles'])->name('home');    
    Route::get('/resources', function () { return view('resources'); })->name('resources');            //controller for these pages will be implemented soon
    Route::get('/instructions', function () { return view('instructions'); })->name('instructions');
    Route::get('/settings', function () { return view('user_settings'); })->name('settings');

    Route::get('/submit',[Research_title_Controller::class, 'showSubmit'] )->name('submit');
    Route::post('/submit',  [Research_title_Controller::class, 'submitTitle'])->name('submit.title'); 
    Route::get('/home/{id}/files', [Research_title_Controller::class, 'manageFiles'])->name('manage.files');
    Route::post('/home/{id}/files/update', [Research_title_Controller::class, 'updateFile'])->name('update.file');  


    Route::post('/submit/ai-check', [AiCheckController::class, 'checkDocuments'])->name('submit.ai_check');


});


// Route::get('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// admin access prolly
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function(){ return view('admin.analytics'); })->name('admin.analytics');
    Route::get('/admin/appointment', function(){ return view('admin.appointment'); })->name('admin.appointment');
    Route::get('/admin/users', function(){ return view('admin.manage_users'); })->name('admin.manage_users');
    Route::get('/admin/staff', function(){ return view('admin.manage_staff'); })->name('admin.manage_staff');
    Route::get('/admin/new', [ admin::class, 'newSubmissions'])->name('admin.NewSubmissions');
    Route::get('/admin/Review', [admin::class, 'GetReview'])->name('admin.Review');
    Route::get('/admin/Revision', [admin::class, 'GetRevision'])->name('admin.Revision');
    Route::get('/admin/file', function (){ return view('admin.view_asessment'); })->name('admin.file');

    Route::get('/admin/applications', [admin::class, 'applications'])->name('admin.applications');
    Route::post('/admin/update-status/{id}', [admin::class, 'updateStatus']);
    Route::post('/admin/{id}/set-initial-review', [admin::class, 'setInitialReview'])->name('submissions.setInitialReview');
Route::get('/admin/view-files/{id}', [admin::class, 'viewFiles'])->name('admin.view_files');

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