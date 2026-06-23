<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    private static array $gamingBrands = ['Sony', 'Nintendo', 'Microsoft', 'Razer', 'Logitech', 'SteelSeries'];

    public function definition(): array
    {
        $purchasePrice = fake()->randomFloat(2, 5000, 200000);
        $salePrice = $purchasePrice * fake()->randomFloat(2, 1.15, 1.45);

        return [
            'category_id' => Category::factory(),
            'reference' => strtoupper(fake()->unique()->bothify('REF-####-??')),
            'barcode' => fake()->unique()->ean13(),
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'brand' => fake()->randomElement(self::$gamingBrands),
            'purchase_price' => $purchasePrice,
            'sale_price' => round($salePrice, 2),
            'stock_quantity' => fake()->numberBetween(0, 100),
            'minimum_stock' => fake()->numberBetween(3, 10),
            'image' => null,
            'is_active' => true,
        ];
    }

    public function lowStock(): static
    {
        return $this->state(function (array $attributes) {
            $min = $attributes['minimum_stock'] ?? 5;

            return [
                'stock_quantity' => fake()->numberBetween(1, $min),
                'minimum_stock' => $min,
            ];
        });
    }

    public function outOfStock(): static
    {
        return $this->state(fn () => ['stock_quantity' => 0]);
    }
}
