<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\CmsContent;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
public function boot()
{
    // Share 'notifications' variable with ALL views that use the layout
    // Adjust 'components.admin_layout' or 'layouts.app' to match your actual layout file name
    View::composer('*', function ($view) {
        if (Auth::check()) {
            static $userNotifications = [];
            static $unreadCounts = [];
            $userId = Auth::id();

            if (!isset($userNotifications[$userId])) {
                $userNotifications[$userId] = UserNotification::where('user_id', $userId)
                                    ->orderBy('created_at', 'desc')
                                    ->take(5) // Limit to 5 for the dropdown
                                    ->get();
                
                $unreadCounts[$userId] = UserNotification::where('user_id', $userId)
                                    ->where('is_read', false)
                                    ->count();
            }

            $view->with('notifications', $userNotifications[$userId])
                 ->with('unreadCount', $unreadCounts[$userId]);

            // Share review workflow counts for Admin and Super Admin sidebars (cached per request)
            if (in_array(Auth::user()->role ?? '', ['admin', 'super_admin'])) {
                static $sidebarCounts = null;
                if ($sidebarCounts === null) {
                    $sidebarCounts = [
                        'pendingIntake' => \App\Models\Research_title::whereIn('Status', [
                            'Pending',
                            'Incomplete',
                            'Incomplete Resubmitted',
                            'Rejected'
                        ])->count(),
                        'activeProtocols' => \App\Models\Research_title::whereIn('Status', [
                            'Incomplete - Awaiting Hardcopy',
                            'Incomplete Hardcopy',
                            'Hardcopy Received',
                            'Reviewer Assigned',
                            'Under Review',
                            'Reviewed'
                        ])->count(),
                        'revisions' => \App\Models\Research_title::whereIn('Status', [
                            'Waiting for Revision',
                            'Revision Submitted',
                            'Reviewing Revisions',
                            'Reviewed',
                            'Panel Deliberation'
                        ])->count(),
                        // Awaiting Certification: Approved but missing Certificate or Approval Letter
                        'pendingCertifications' => \App\Models\Research_title::where('Status', 'Approved')
                            ->where(function ($query) {
                                $query->whereDoesntHave('adminFiles', function ($q) {
                                    $q->where('filetype', 'certificate');
                                })->orWhereDoesntHave('adminFiles', function ($q) {
                                    $q->where('filetype', 'Approval Letter');
                                });
                            })->count(),
                        // Certified: Approved and has both Certificate & Approval Letter
                        'totalCertified' => \App\Models\Research_title::where('Status', 'Approved')
                            ->whereHas('adminFiles', function ($q) {
                                $q->where('filetype', 'certificate');
                            })->whereHas('adminFiles', function ($q) {
                                $q->where('filetype', 'Approval Letter');
                            })->count(),
                    ];
                }
                $view->with('sidebarCounts', $sidebarCounts);
            }
        }
        
        // Share CMS content globally (cached per request)
        static $cms = null;
        if ($cms === null) {
            $cms = CmsContent::all()->pluck('value', 'key');
        }
        $view->with('cms', $cms);
    });
}
}
