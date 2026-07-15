<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Logique métier du catalogue produits.
 */
class ProductService
{
    public function __construct(
        private readonly ActivityLogService $activityLog
    ) {}

    public function paginate(array $filters = [], int $perPage = 30): LengthAwarePaginator
    {
        $allowedSorts = ['name', 'reference', 'sale_price', 'stock_quantity', 'created_at'];
        $sort = in_array($filters['sort'] ?? '', $allowedSorts, true) ? $filters['sort'] : 'created_at';
        $direction = ($filters['direction'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        return Product::query()
            ->with('category')
            ->search($filters['search'] ?? null)
            ->filter([
                'category_id' => $filters['category_id'] ?? null,
                'brand' => $filters['brand'] ?? null,
                'is_active' => isset($filters['is_active']) && $filters['is_active'] !== ''
                    ? (bool) $filters['is_active']
                    : null,
                'stock_status' => $filters['stock_status'] ?? null,
            ])
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getBrands(): array
    {
        return Product::query()
            ->whereNotNull('brand')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand')
            ->all();
    }

    public function create(array $data, ?UploadedFile $image = null): Product
    {
        if ($image) {
            $data['image'] = $this->storeImage($image);
        }

        $product = Product::create($data);

        $this->activityLog->log('create', $product, "Produit créé : {$product->name} ({$product->reference})");

        return $product;
    }

    public function update(Product $product, array $data, ?UploadedFile $image = null, bool $removeImage = false): Product
    {
        if ($removeImage && $product->image) {
            $this->deleteImage($product->image);
            $data['image'] = null;
        }

        if ($image) {
            if ($product->image) {
                $this->deleteImage($product->image);
            }
            $data['image'] = $this->storeImage($image);
        }

        $product->update($data);

        $this->activityLog->log('update', $product, "Produit modifié : {$product->name}");

        return $product->fresh();
    }

    public function delete(Product $product): void
    {
        if ($product->saleItems()->exists()) {
            throw new \RuntimeException('Impossible de supprimer un produit lié à des ventes.');
        }

        $name = $product->name;

        if ($product->image) {
            $this->deleteImage($product->image);
        }

        $product->delete();

        $this->activityLog->log('delete', null, "Produit supprimé : {$name}");
    }

    private function storeImage(UploadedFile $image): string
    {
        return $image->store('products', 'public');
    }

    private function deleteImage(string $path): void
    {
        Storage::disk('public')->delete($path);
    }
}
