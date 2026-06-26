<?php

namespace App\Services;

use App\Enums\NotificationType;
use App\Models\AppNotification;
use App\Models\Product;
use App\Models\User;

/**
 * Génère une notification d'alerte stock pour un utilisateur, typiquement
 * déclenchée à la connexion (voir NotifyStockAlertsOnLogin).
 */
class StockAlertNotificationService
{
    /**
     * Crée une notification d'alerte stock si des produits sont en rupture
     * ou en stock faible, et si l'utilisateur n'a pas déjà été notifié
     * aujourd'hui (évite de spammer à chaque connexion).
     */
    public function notifyIfNeeded(User $user): ?AppNotification
    {
        $outOfStockCount = Product::outOfStock()->count();
        $lowStockCount = Product::lowStock()->count();

        if ($outOfStockCount === 0 && $lowStockCount === 0) {
            return null;
        }

        $alreadyNotifiedToday = AppNotification::forUser($user->id)
            ->whereIn('type', [NotificationType::LowStock, NotificationType::OutOfStock])
            ->whereDate('created_at', today())
            ->exists();

        if ($alreadyNotifiedToday) {
            return null;
        }

        $parts = [];
        if ($outOfStockCount > 0) {
            $parts[] = "{$outOfStockCount} produit(s) en rupture de stock";
        }
        if ($lowStockCount > 0) {
            $parts[] = "{$lowStockCount} produit(s) en stock faible";
        }

        return AppNotification::create([
            'user_id' => $user->id,
            'type' => $outOfStockCount > 0 ? NotificationType::OutOfStock : NotificationType::LowStock,
            'title' => 'Alertes stock',
            'message' => implode(' et ', $parts) . '.',
            'data' => [
                'out_of_stock_count' => $outOfStockCount,
                'low_stock_count' => $lowStockCount,
            ],
        ]);
    }
}
