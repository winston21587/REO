<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\admin;
use App\Http\Controllers\Research_title_Controller;
use App\Http\Controllers\AiCheckController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Auth; // Needed for Auth::id()
use App\Models\UserNotification; // Needed for the Notification route

// ====================================================
// PUBLIC ROUTES
// ====================================================

Route::get('/', function () {
    return view('index'); 
})->name('index');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login'); 
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register/internal', [AuthController::class, 'register_internal'])->name('register.internal');
    Route::post('register/external', [AuthController::class, 'register_external'])->name('register.external');

    Route::get('/verify', [AuthController::class, 'showVerifyForm'])->name('verify.show'); 
    Route::post('/verify', [AuthController::class, 'verifyCode'])->name('verify.submit'); 

    // Forgot Password
    Route::get('forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('forgot-password', [AuthController::class, 'sendResetCode'])->name('password.email');
    Route::get('reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
}); 

Route::get('/legal/privacy-policy', function () { return view('legal.privacy'); })->name('policy.privacy');
Route::get('/legal/terms-of-service', function () { return view('legal.terms'); })->name('policy.terms');
Route::get('/legal/accessibility', function () { return view('legal.accessibility'); })->name('policy.accessibility');


// ====================================================
// AUTHENTICATED ROUTES (Shared by ALL logged-in users)
// ====================================================
Route::middleware(['auth'])->group(function () {
    
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('logout', [AuthController::class, 'logout']); // Fallback if needed
    Route::post('/accept-terms', [AuthController::class, 'acceptTerms'])->name('accept.terms');

    // --- NOTIFICATION ROUTES (Accessible by Admin & Researcher) ---
    Route::get('/notifications', function () {
        $notifications = UserNotification::where('user_id', Auth::id())
                            ->orderBy('created_at', 'desc')
                            ->get();
        return view('user.notifications', compact('notifications'));
    })->name('notifications.index');

    Route::post('/notifications/{id}/read', function ($id) {
        $notification = UserNotification::findOrFail($id);
        // Security check: ensure user owns the notification
        if ($notification->user_id == Auth::id()) {
            $notification->update(['is_read' => true]);
        }
        return back();
    })->name('notifications.read');
    Route::post('/notifications/mark-all-read', function () {
    UserNotification::where('user_id', Auth::id())
        ->where('is_read', false)
        ->update(['is_read' => true]);
    return back();
})->name('notifications.markAllRead');


    // ====================================================
    // RESEARCHER ROUTES
    // ====================================================
    Route::middleware(['role:researcher'])->group(function () {
        Route::get('/home', [Research_title_Controller::class, 'showTitles'])->name('home');    
        Route::get('/resources', function () { return view('resources'); })->name('resources');
        Route::get('/instructions', function () { return view('instructions'); })->name('instructions');
        Route::get('/settings', function () { return view('user_settings'); })->name('settings');

        Route::get('/submit', [Research_title_Controller::class, 'showSubmit'])->name('submit');
        Route::post('/submit', [Research_title_Controller::class, 'submitTitle'])->name('submit.title'); 
        Route::get('/home/{id}/files', [Research_title_Controller::class, 'manageFiles'])->name('manage.files');
        Route::post('/home/{id}/files/update', [Research_title_Controller::class, 'updateFile'])->name('update.file');  
        Route::post('/submit/ai-check', [AiCheckController::class, 'checkDocuments'])->name('submit.ai_check');

        // Settings Routes
        Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.update_profile');
        Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.update_password');
        Route::delete('/settings/account', [SettingsController::class, 'deleteAccount'])->name('settings.delete_account');
    });

    // ====================================================
    // ADMIN ROUTES
    // ====================================================
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', function(){ return view('admin.analytics'); })->name('admin.analytics');
        Route::get('/admin/appointment', function(){ return view('admin.appointment'); })->name('admin.appointment');
        Route::get('/admin/users', function(){ return view('admin.manage_users'); })->name('admin.manage_users');
        Route::get('/admin/staff', function(){ return view('admin.manage_staff'); })->name('admin.manage_staff');

        Route::get('/admin/meetings', [admin::class, 'meetings'])->name('admin.meetings');
        Route::get('/admin/new', [admin::class, 'newSubmissions'])->name('admin.NewSubmissions');
        Route::get('/admin/Review', [admin::class, 'GetReview'])->name('admin.Review');
        Route::get('/admin/Revision', [admin::class, 'GetRevision'])->name('admin.Revision');
        Route::get('/admin/file', function (){ return view('admin.view_asessment'); })->name('admin.file');

        Route::get('/admin/applications', [admin::class, 'applications'])->name('admin.applications');
        
        // The Main Update Logic (Covers Triage Modal)
        Route::post('/admin/update-status/{id}', [admin::class, 'updateStatus'])->name('admin.updateStatus');
        
        Route::post('/admin/assign-reviewers/{id}', [admin::class, 'assignReviewers'])->name('admin.assignReviewers');
        Route::get('/admin/view-files/{id}', [admin::class, 'viewFiles'])->name('admin.view_files');

        // --- DEPRECATED ROUTES (Logic merged into updateStatus) ---
        // Route::post('/admin/{id}/set-initial-review', [admin::class, 'setInitialReview'])->name('submissions.setInitialReview');
        // Route::post('/admin/submission/{id}/complete', [admin::class, 'markAsComplete'])->name('admin.markComplete');
        // Route::post('/admin/submission/{id}/incomplete', [admin::class, 'markAsIncomplete'])->name('admin.markIncomplete');
    });

});