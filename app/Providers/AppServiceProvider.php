<?php

namespace App\Providers;

use App\Models\AppNotification;
use App\Services\MenuService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer(['layouts.partials.sidebar', 'layouts.partials.navbar'], function ($view) {
            $user = auth()->user();

            if ($user) {
                $user->loadMissing('role');
                $view->with('menuItems', app(MenuService::class)->forUser($user));
                $view->with('unreadCount', AppNotification::forUser($user->id)->unread()->count());
            }
        });
    }
}
