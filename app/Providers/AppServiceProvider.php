<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
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
            $notifications = UserNotification::where('user_id', Auth::id())
                                ->orderBy('created_at', 'desc')
                                ->take(5) // Limit to 5 for the dropdown
                                ->get();
            
            $unreadCount = UserNotification::where('user_id', Auth::id())
                                ->where('is_read', false)
                                ->count();

            $view->with('notifications', $notifications)
                 ->with('unreadCount', $unreadCount);
        }
    });
}
}
