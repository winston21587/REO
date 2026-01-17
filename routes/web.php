<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
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
        return view('notifications', compact('notifications'));
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
        Route::post('/home/{id}/files/submit', [Research_title_Controller::class, 'submitRevisions'])->name('submit.revisions');
        Route::post('/submit/ai-check', [AiCheckController::class, 'checkDocuments'])->name('submit.ai_check');
        Route::get('/home/{id}/recommendation-letter', [Research_title_Controller::class, 'viewRecommendationLetter'])->name('recommendation.view');

        // Settings Routes
        Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.update_profile');
        Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.update_password');
        Route::post('/settings/preferences/email', [SettingsController::class, 'updateEmailPreferences'])->name('settings.update_email_preferences');
        Route::post('/settings/preferences/display', [SettingsController::class, 'updateDisplayPreferences'])->name('settings.update_display_preferences');
        Route::delete('/settings/account', [SettingsController::class, 'deleteAccount'])->name('settings.delete_account');
    });

    // ====================================================
    // ADMIN ROUTES
    // ====================================================
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'analytics'])->name('admin.analytics');
        Route::get('/admin/appointment', function(){ return view('admin.appointment'); })->name('admin.appointment');
        Route::get('/admin/users', [AdminController::class, 'manageUsers'])->name('admin.manage_users');
        Route::post('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/admin/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle_status');
        Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
        
        Route::get('/admin/staff', [AdminController::class, 'manageStaff'])->name('admin.manage_staff');
        Route::post('/admin/staff', [AdminController::class, 'storeStaff'])->name('admin.staff.store');
        Route::put('/admin/staff/{id}', [AdminController::class, 'updateStaff'])->name('admin.staff.update');
        Route::delete('/admin/staff/{id}', [AdminController::class, 'deleteStaff'])->name('admin.staff.delete');

        Route::get('/admin/meetings', [AdminController::class, 'meetings'])->name('admin.meetings');
        Route::post('/admin/meetings', [AdminController::class, 'storeMeeting'])->name('admin.meetings.store');
        Route::get('/admin/meetings/{id}', [AdminController::class, 'showMeeting'])->name('admin.meetings.show');
    Route::delete('/admin/meetings/{id}', [AdminController::class, 'destroyMeeting'])->name('admin.meetings.destroy');
        Route::post('/admin/meetings/{id}/agenda', [AdminController::class, 'storeAgendaItem'])->name('admin.meetings.agenda.store');
        Route::put('/admin/agenda/{id}', [AdminController::class, 'updateAgendaItem'])->name('admin.agenda.update');
        Route::delete('/admin/agenda/{id}', [AdminController::class, 'destroyAgendaItem'])->name('admin.agenda.destroy');
        Route::put('/admin/meetings/{id}/status', [AdminController::class, 'updateMeetingStatus'])->name('admin.meetings.status');
        Route::get('/admin/new', [AdminController::class, 'newSubmissions'])->name('admin.NewSubmissions');
        Route::get('/admin/Review', [AdminController::class, 'GetReview'])->name('admin.Review');
        Route::get('/admin/Revision', [AdminController::class, 'GetRevision'])->name('admin.Revision');
        Route::get('/admin/file', function (){ return view('admin.view_asessment'); })->name('admin.file');

        Route::get('/admin/applications', [AdminController::class, 'applications'])->name('admin.applications');
        
        // The Main Update Logic (Covers Triage Modal)
        Route::post('/admin/update-status/{id}', [AdminController::class, 'updateStatus'])->name('admin.updateStatus');
        
    Route::get('/admin/revisions', [AdminController::class, 'revisions'])->name('admin.revisions');
    Route::get('/admin/certifications', [AdminController::class, 'certifications'])->name('admin.certifications');
    Route::get('/admin/view_files/{id}', [AdminController::class, 'viewFiles'])->name('admin.view_files');
    Route::get('/admin/file-serve/{id}', [AdminController::class, 'serveFile'])->name('admin.serve_file');

        Route::post('/admin/assign-reviewers/{id}', [AdminController::class, 'assignReviewers'])->name('admin.assignReviewers');

        Route::get('/admin/letter/create/{id}', [AdminController::class, 'showLetterForm'])->name('admin.letter.form');
        Route::post('/admin/letter/preview', [AdminController::class, 'previewLetter'])->name('admin.letter.preview');
        Route::get('/admin/check-file-status/{id}', [AdminController::class, 'checkFileStatus']);
        Route::post('/admin/analyze-protocol-type/{id}', [AiCheckController::class, 'analyzeProtocolType'])->name('admin.analyze_protocol');
        
        // Recommendation Letter Routes
        Route::get('/admin/recommendation-letter/{id}', [AdminController::class, 'showRecommendationLetterForm'])->name('admin.recommendation.form');
        Route::post('/admin/recommendation-letter/generate', [AdminController::class, 'generateRecommendationLetter'])->name('admin.recommendation.generate');
        // --- DEPRECATED ROUTES (Logic merged into updateStatus) ---
        // Route::post('/admin/{id}/set-initial-review', [AdminController::class, 'setInitialReview'])->name('submissions.setInitialReview');
        // Route::post('/admin/submission/{id}/complete', [AdminController::class, 'markAsComplete'])->name('admin.markComplete');
        // Route::post('/admin/submission/{id}/incomplete', [AdminController::class, 'markAsIncomplete'])->name('admin.markIncomplete');
        Route::post('/admin/certificate/upload/{id}', [AdminController::class, 'uploadCertificate'])->name('admin.certificate.upload');
        Route::post('/admin/recommendation-letter/finalize/{id}', [AdminController::class, 'finalizeReview'])->name('admin.recommendation.finalize');
        Route::get('/admin/recommendation-letter/view-saved/{id}', [AdminController::class, 'viewSavedRecommendationLetter'])->name('admin.recommendation.view_saved');
    });
});