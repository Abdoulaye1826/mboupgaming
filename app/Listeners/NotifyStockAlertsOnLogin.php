<?php

namespace App\Listeners;

use App\Services\StockAlertNotificationService;
use Illuminate\Auth\Events\Login;

/**
 * Notifie l'utilisateur des alertes stock (rupture / stock faible) dès sa
 * connexion, au plus une fois par jour pour ne pas le spammer.
 */
class NotifyStockAlertsOnLogin
{
    public function __construct(
        private readonly StockAlertNotificationService $stockAlertNotificationService
    ) {
    }

    public function handle(Login $event): void
    {
        $this->stockAlertNotificationService->notifyIfNeeded($event->user);
    }
}
