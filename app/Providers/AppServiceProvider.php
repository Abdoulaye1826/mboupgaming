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
        // Charge le helper entreprise() sans dépendre de l'entrée
        // composer.json "autoload.files" — celle-ci n'est effective
        // qu'après un `composer install`/`dump-autoload`, indisponible sur
        // certains hébergements mutualisés (composer absent du PATH SSH).
        // AppServiceProvider, lui, est toujours chargé via l'autoload PSR-4
        // normal, donc ce require_once est garanti à chaque déploiement.
        require_once app_path('helpers.php');
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
                $view->with('recentNotifications', AppNotification::forUser($user->id)
                    ->latest()
                    ->limit(6)
                    ->get());
            }
        });
    }
}
