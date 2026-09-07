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
