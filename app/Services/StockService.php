<?php

namespace App\Services;

use App\Enums\StockMovementType;
use App\Models\StockMovement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Logique métier pour les mouvements de stock.
 */
class StockService
{
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
