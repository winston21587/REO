<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Research_title_Controller;
use App\Http\Controllers\AiCheckController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Auth; // Needed for Auth::id()
use App\Models\UserNotification; // Needed for the Notification route
use App\Models\CmsContent;
use App\Http\Controllers\CmsController;


// ====================================================
// PUBLIC ROUTES
// ====================================================

Route::get('/', function () {
    $contents = CmsContent::all()->pluck('value', 'key');
    return view('index', compact('contents'));
})->name('index');

Route::get('/test-model', function () {
    return view('test_model');
})->name('test.model');
Route::get('/test_model', function () { // Added this to match your typo!
    return view('test_model');
});
Route::post('/predict-model', [\App\Http\Controllers\PredictionController::class, 'predict'])->name('predict.model');

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

Route::get('/legal/privacy-policy', function () {
    $contents = CmsContent::all()->pluck('value', 'key');
    return view('legal.privacy', compact('contents'));
})->name('policy.privacy');

Route::get('/legal/terms-of-service', function () {
    $contents = CmsContent::all()->pluck('value', 'key');
    return view('legal.terms', compact('contents'));
})->name('policy.terms');

Route::get('/legal/accessibility', function () {
    $contents = CmsContent::all()->pluck('value', 'key');
    return view('legal.accessibility', compact('contents'));
})->name('policy.accessibility');

Route::get('/download-mobile-app', function () {
    return view('components.mobile_download');
})->name('mobile.download');

Route::get('logout', [AuthController::class, 'logout']); // Fallback if needed




// ====================================================
// AUTHENTICATED ROUTES (Shared by ALL logged-in users)
// ====================================================
Route::middleware(['auth'])->group(function () {

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/accept-terms', [AuthController::class, 'acceptTerms'])->name('accept.terms');

    // Force Password Change Routes
    Route::get('/password/change', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/password/change', [AuthController::class, 'updatePassword'])->name('password.updateFirstLogin');

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

    // API endpoint to check for unread notifications (AJAX)
    Route::get('/api/notifications/unread', function () {
        $unreadCount = UserNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        $notifications = UserNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications
        ]);
    })->name('notifications.api.unread');

    // Route to view a specific notification and its related content
    Route::get('/notifications/{id}', function ($id) {
        $notification = UserNotification::findOrFail($id);

        // Security check: ensure user owns the notification
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Mark as read
        if (!$notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        // Redirect to the related research submission files if it exists
        if ($notification->research_id) {
            if (Auth::check() && Auth::user()->role === 'reviewer') {
                return redirect()->route('reviewer.view_files', $notification->research_id);
            }
            return redirect()->route('manage.files', $notification->research_id);
        }

        // Otherwise, go to notifications index
        return redirect()->route('notifications.index');
    })->name('notifications.show');


    // ====================================================
    // RESEARCHER ROUTES
    // ====================================================
    Route::middleware(['role:researcher'])->group(function () {
        Route::get('/home', [Research_title_Controller::class, 'showTitles'])->name('home');
        Route::get('/resources', function () {
            $contents = CmsContent::all()->pluck('value', 'key');
            $downloadables = \App\Models\DownloadableResource::all();
            return view('resources', compact('contents', 'downloadables'));
        })->name('resources');

        Route::get('/instructions', function () {
            $contents = CmsContent::all()->pluck('value', 'key');
            return view('instructions', compact('contents'));
        })->name('instructions');
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

        Route::get('/submit', [Research_title_Controller::class, 'showSubmit'])->name('submit');
        Route::post('/submit', [Research_title_Controller::class, 'submitTitle'])->name('submit.title');
        Route::get('/home/{id}/files', [Research_title_Controller::class, 'manageFiles'])->name('manage.files');
        Route::put('/submissions/update-file/{id}', [Research_title_Controller::class, 'updateFile'])->name('update.file');
        Route::post('/submissions/add-missing-file/{id}', [Research_title_Controller::class, 'addMissingFile'])->name('add.missing.file');
        Route::post('/submissions/upload-revision-document/{id}', [Research_title_Controller::class, 'uploadRevisionDocument'])->name('upload.revision.document');
        Route::delete('/submissions/delete-revision-document/{file_id}', [Research_title_Controller::class, 'deleteRevisionDocument'])->name('delete.revision.document');
        Route::post('/home/{id}/files/submit', [Research_title_Controller::class, 'submitRevisions'])->name('submit.revisions');
        Route::post('/submit/ai-check', [AiCheckController::class, 'checkDocuments'])->name('submit.ai_check');
        Route::get('/home/{id}/recommendation-letter', [Research_title_Controller::class, 'viewRecommendationLetter'])->name('recommendation.view');

        // Official Receipt Upload Route for Researchers (DEPRECATED: OR is now required at submission time)
        // Route::post('/researcher/submit-or/{id}', [\App\Http\Controllers\ORNumberController::class, 'researcherSubmitOR'])->name('researcher.submit_or');

        // CV Classification Correction (only allowed after admin marks CV as Invalid)
        Route::post('/researcher/cv-correct/{id}', [\App\Http\Controllers\CVVerificationController::class, 'researcherCorrectProjectType'])->name('researcher.cv.correct');

        // Settings Routes
        Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.update_profile');
        Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.update_password');
        Route::post('/settings/preferences/email', [SettingsController::class, 'updateEmailPreferences'])->name('settings.update_email_preferences');
        Route::post('/settings/preferences/display', [SettingsController::class, 'updateDisplayPreferences'])->name('settings.update_display_preferences');
        Route::delete('/settings/account', [SettingsController::class, 'deleteAccount'])->name('settings.delete_account');
    });

    // ====================================================
    // SUPER ADMIN ROUTES
    // ====================================================
    Route::middleware(['super_admin'])->group(function () {
        Route::get('/super-admin', [AdminController::class, 'superAdminAnalytics'])->name('super_admin.analytics');
        Route::get('/super-admin/analytics/export', [AdminController::class, 'exportCsv'])->name('super_admin.analytics.export');


        // Manage Admins
        Route::get('/super-admin/admins', [\App\Http\Controllers\SuperAdminController::class, 'manageAdmins'])->name('super_admin.manage_admins');
        Route::post('/super-admin/admins', [\App\Http\Controllers\SuperAdminController::class, 'createAdmin'])->name('super_admin.admins.create');
        Route::post('/super-admin/admins/{user}/toggle-status', [\App\Http\Controllers\SuperAdminController::class, 'toggleAdminStatus'])->name('super_admin.admins.toggle_status');
        Route::delete('/super-admin/admins/{user}', [\App\Http\Controllers\SuperAdminController::class, 'deleteAdmin'])->name('super_admin.admins.delete');

        // Manage Reviewers
        Route::get('/super-admin/reviewers', [\App\Http\Controllers\SuperAdminController::class, 'manageReviewers'])->name('super_admin.manage_reviewers');
        Route::post('/super-admin/reviewers', [\App\Http\Controllers\SuperAdminController::class, 'createReviewer'])->name('super_admin.reviewers.create');
        Route::post('/super-admin/reviewers/{user}/toggle-status', [\App\Http\Controllers\SuperAdminController::class, 'toggleReviewerStatus'])->name('super_admin.reviewers.toggle_status');
        Route::delete('/super-admin/reviewers/{user}', [\App\Http\Controllers\SuperAdminController::class, 'deleteReviewer'])->name('super_admin.reviewers.delete');

        // Manage Fees & Revenue Logs
        Route::get('/super-admin/manage-fees', [\App\Http\Controllers\SuperAdminFeeController::class, 'manageFees'])->name('super_admin.manage_fees');
        Route::post('/super-admin/manage-fees', [\App\Http\Controllers\SuperAdminFeeController::class, 'storeFee'])->name('super_admin.fees.store');
        Route::put('/super-admin/manage-fees/{id}', [\App\Http\Controllers\SuperAdminFeeController::class, 'updateFee'])->name('super_admin.fees.update');
        Route::delete('/super-admin/manage-fees/{id}', [\App\Http\Controllers\SuperAdminFeeController::class, 'destroyFee'])->name('super_admin.fees.destroy');
        Route::get('/super-admin/revenue-logs', [\App\Http\Controllers\SuperAdminFeeController::class, 'revenueLogs'])->name('super_admin.revenue_logs');

        // Document Requirements (Moved from Admin)
        Route::get('/admin/manage-documents', [\App\Http\Controllers\DocumentRequirementController::class, 'index'])->name('admin.manage_documents');
        Route::post('/admin/document-requirements', [\App\Http\Controllers\DocumentRequirementController::class, 'store'])->name('admin.document_requirements.store');
        Route::put('/admin/document-requirements/{id}', [\App\Http\Controllers\DocumentRequirementController::class, 'update'])->name('admin.document_requirements.update');
        Route::delete('/admin/document-requirements/{id}', [\App\Http\Controllers\DocumentRequirementController::class, 'destroy'])->name('admin.document_requirements.destroy');

        // CMS Routes (Moved from Admin)
        Route::controller(CmsController::class)->prefix('admin/cms')->name('admin.cms.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/content', 'content')->name('content');
            Route::post('/content', 'updateContent')->name('content.update');

            // Downloadable Resources
            Route::post('/downloadables', 'storeDownloadable')->name('downloadables.store');
            Route::put('/downloadables/{id}', 'updateDownloadable')->name('downloadables.update');
            Route::delete('/downloadables/{id}', 'destroyDownloadable')->name('downloadables.destroy');
            Route::get('/categories', 'categories')->name('categories');
            Route::post('/categories', 'storeCategory')->name('categories.store');
            Route::put('/categories/{id}', 'updateCategory')->name('categories.update');
            Route::delete('/categories/{id}', 'destroyCategory')->name('categories.destroy');
            Route::post('/colleges', 'storeCollege')->name('colleges.store');
            Route::put('/colleges/{id}', 'updateCollege')->name('colleges.update');
            Route::delete('/colleges/{id}', 'destroyCollege')->name('colleges.destroy');
            Route::get('/departments', 'departments')->name('departments');
            Route::post('/departments', 'storeDepartment')->name('departments.store');
            Route::put('/departments/{id}', 'updateDepartment')->name('departments.update');
            Route::delete('/departments/{id}', 'destroyDepartment')->name('departments.destroy');
            Route::post('/programs', 'storeProgram')->name('programs.store');
            Route::put('/programs/{id}', 'updateProgram')->name('programs.update');
            Route::delete('/programs/{id}', 'destroyProgram')->name('programs.destroy');
        });
    });

    // ====================================================
    // ADMIN ROUTES
    // ====================================================
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'analytics'])->name('admin.analytics');
        Route::get('/admin/analytics/export', [AdminController::class, 'exportCsv'])->name('admin.analytics.export');

        Route::get('/admin/appointment', function () {
            return view('admin.appointment');
        })->name('admin.appointment');
        Route::get('/admin/users', [AdminController::class, 'manageUsers'])->name('admin.manage_users');
        Route::post('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/admin/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle_status');
        Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');


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
        Route::get('/admin/file', function () {
            return view('admin.view_asessment');
        })->name('admin.file');

        Route::get('/admin/applications', [AdminController::class, 'applications'])->name('admin.applications');
        Route::post('/admin/applications/{id}/assign-reviewers', [AdminController::class, 'assignReviewers'])->name('admin.assign_reviewers');

        // The Main Update Logic (Covers Triage Modal)
        Route::post('/admin/update-status/{id}', [AdminController::class, 'updateStatus'])->name('admin.updateStatus');
        Route::get('/admin/reviewer-feedback/{id}', [AdminController::class, 'getReviewerFeedback'])->name('admin.reviewerFeedback');

        // Official Receipt Number Logging (Admin Access)
        Route::post('/admin/or-number/verify/{id}', [\App\Http\Controllers\ORNumberController::class, 'verifyOR'])->name('admin.or_number.verify');
        Route::post('/admin/or-number/reject/{id}', [\App\Http\Controllers\ORNumberController::class, 'rejectOR'])->name('admin.or_number.reject');

        // CV Classification Verification (Admin Access)
        Route::post('/admin/cv-verify/{id}', [\App\Http\Controllers\CVVerificationController::class, 'verifyCv'])->name('admin.cv.verify');
        Route::post('/admin/cv-invalidate/{id}', [\App\Http\Controllers\CVVerificationController::class, 'invalidateCv'])->name('admin.cv.invalidate');

        // IRB Prediction API Route
        Route::post('/admin/predict', [\App\Http\Controllers\PredictController::class, 'predict'])->name('admin.predict');
        Route::post('/admin/predict/save', [\App\Http\Controllers\PredictController::class, 'save'])->name('admin.predict.save');

        // AI Analysis Route for Modals
        Route::post('/admin/analyze-protocol-type/{id}', [AiCheckController::class, 'analyzeProtocolType'])->name('admin.analyze_protocol_type');

        Route::get('/admin/revisions', [AdminController::class, 'revisions'])->name('admin.revisions');
        Route::get('/admin/certifications', [AdminController::class, 'certifications'])->name('admin.certifications');
        Route::get('/admin/certifications/{id}/generate', [AdminController::class, 'showGenerateCertificates'])->name('admin.certificate.generate_page');
        Route::get('/admin/view_files/{id}', [AdminController::class, 'viewFiles'])->name('admin.view_files');
        Route::get('/admin/file-serve/{id}', [AdminController::class, 'serveFile'])->name('admin.serve_file');
        Route::post('/admin/certificate/generate/{id}', [AdminController::class, 'generateCertificate'])->name('admin.certificate.generate');



        // Recommendation Letter Routes
        Route::get('/admin/recommendation-letter/{id}', [AdminController::class, 'showRecommendationLetterForm'])->name('admin.recommendation.form');
        Route::post('/admin/recommendation-letter/generate', [AdminController::class, 'generateRecommendationLetter'])->name('admin.recommendation.generate');
        Route::post('/admin/recommendation-letter/finalize/{id}', [AdminController::class, 'finalizeReview'])->name('admin.recommendation.finalize');
        Route::get('/admin/recommendation-letter/view-saved/{id}', [AdminController::class, 'viewSavedRecommendationLetter'])->name('admin.recommendation.view_saved');
        Route::get('/admin/recommendation-letter/view-file/{fileId}', [AdminController::class, 'viewRecommendationLetterFile'])->name('admin.recommendation.view_file');
        // Notify researcher that their official receipt is required
        Route::post('/admin/protocols/{id}/notify-receipt', [AdminController::class, 'notifyReceiptRequired'])->name('admin.notify_receipt_required');

    });

    // ====================================================
    // REVIEWER ROUTES
    // ====================================================
    Route::middleware(['role:reviewer'])->group(function () {
        Route::get('/reviewer', [\App\Http\Controllers\ReviewerController::class, 'index'])->name('reviewer.dashboard');
        Route::get('/reviewer/re-evaluation', [\App\Http\Controllers\ReviewerController::class, 'reEvaluation'])->name('reviewer.reevaluation');
        Route::get('/reviewer/reviewed-titles', [\App\Http\Controllers\ReviewerController::class, 'reviewedTitles'])->name('reviewer.reviewed_titles');
        Route::get('/reviewer/view-files/{id}', [\App\Http\Controllers\ReviewerController::class, 'viewFiles'])->name('reviewer.view_files');
        Route::get('/reviewer/file-serve/{id}', [\App\Http\Controllers\ReviewerController::class, 'serveFile'])->name('reviewer.serve_file');
        Route::delete('/reviewer/file-delete/{id}', [\App\Http\Controllers\ReviewerController::class, 'deleteFile'])->name('reviewer.file.delete');
        Route::post('/reviewer/protocols/{id}/upload', [\App\Http\Controllers\ReviewerController::class, 'uploadFile'])->name('reviewer.upload');
        Route::post('/reviewer/protocols/{id}/complete', [\App\Http\Controllers\ReviewerController::class, 'completeReview'])->name('reviewer.complete_review');
        Route::post('/reviewer/file-remark/{fileId}', [\App\Http\Controllers\ReviewerController::class, 'saveFileRemark'])->name('reviewer.save_file_remark');
    });

});