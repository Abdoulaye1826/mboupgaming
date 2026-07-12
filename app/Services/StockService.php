<?php

namespace App\Services;

use App\Enums\StockMovementType;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * Logique métier pour les mouvements de stock.
 */
class StockService
{
    /**
     * Ajuste manuellement le stock d'un produit (boutons +/- sur la fiche
     * produit) et journalise le mouvement. Réservé aux produits qui ne sont
     * pas suivis par IMEI : pour ceux-ci, le stock découle uniquement du
     * nombre d'IMEI disponibles (voir ProductImeiService).
     */
    public function adjust(Product $product, string $direction, int $quantity, ?string $reason = null): StockMovement
    {
        if (!in_array($direction, ['in', 'out'], true)) {
            throw new \RuntimeException('Direction de mouvement invalide.');
        }

        if ($quantity <= 0) {
            throw new \RuntimeException('La quantité doit être supérieure à zéro.');
        }

        if ($product->tracks_imei) {
            throw new \RuntimeException('Ce produit est suivi par IMEI : ajoutez ou retirez un IMEI plutôt qu\'une quantité.');
        }

        $quantityBefore = $product->stock_quantity;

        if ($direction === 'out' && $quantity > $quantityBefore) {
            throw new \RuntimeException('Stock insuffisant pour retirer cette quantité.');
        }

        $quantityAfter = $direction === 'in' ? $quantityBefore + $quantity : $quantityBefore - $quantity;

        return DB::transaction(function () use ($product, $direction, $quantity, $quantityBefore, $quantityAfter, $reason) {
            $product->update(['stock_quantity' => $quantityAfter]);

            return StockMovement::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'type' => $direction === 'in' ? StockMovementType::Entry : StockMovementType::Exit,
                'quantity' => $quantity,
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityAfter,
                'reason' => $reason !== null && $reason !== ''
                    ? $reason
                    : ($direction === 'in' ? 'Ajout manuel de stock' : 'Retrait manuel de stock'),
            ]);
        });
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return StockMovement::query()
            ->with(['product', 'supplier', 'user'])
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('product', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                              ->orWhere('reference', 'like', "%{$search}%")
                              ->orWhere('barcode', 'like', "%{$search}%");
                    })
                    ->orWhereHas('supplier', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhere('reference', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%");
                });
            })
            ->when($filters['type'] ?? null, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($filters['product_id'] ?? null, function ($query, $productId) {
                $query->where('product_id', $productId);
            })
            ->when($filters['supplier_id'] ?? null, function ($query, $supplierId) {
                $query->where('supplier_id', $supplierId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @return array<string, string>
     */
    public function getTypes(): array
    {
        return collect(StockMovementType::cases())
            ->mapWithKeys(fn (StockMovementType $type) => [$type->value => $type->label()])
            ->all();
    }
}
