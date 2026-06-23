<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

/**
 * Seed du catalogue produits gaming de démonstration.
 */
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Consoles
            ['category' => 'consoles', 'reference' => 'PS5-SLIM-001', 'name' => 'PlayStation 5 Slim', 'brand' => 'Sony', 'purchase' => 350000, 'sale' => 420000, 'stock' => 15, 'min' => 5],
            ['category' => 'consoles', 'reference' => 'SWITCH-OLED-001', 'name' => 'Nintendo Switch OLED', 'brand' => 'Nintendo', 'purchase' => 220000, 'sale' => 275000, 'stock' => 20, 'min' => 5],
            ['category' => 'consoles', 'reference' => 'XBOX-SX-001', 'name' => 'Xbox Series X', 'brand' => 'Microsoft', 'purchase' => 380000, 'sale' => 450000, 'stock' => 8, 'min' => 3],
            // Jeux
            ['category' => 'jeux', 'reference' => 'GOW-RAGNAROK', 'name' => 'God of War Ragnarök PS5', 'brand' => 'Sony', 'purchase' => 35000, 'sale' => 55000, 'stock' => 30, 'min' => 10],
            ['category' => 'jeux', 'reference' => 'ZELDA-TOTK', 'name' => 'Zelda: Tears of the Kingdom', 'brand' => 'Nintendo', 'purchase' => 40000, 'sale' => 60000, 'stock' => 25, 'min' => 10],
            ['category' => 'jeux', 'reference' => 'FC25-PS5', 'name' => 'EA Sports FC 25 PS5', 'brand' => 'Sony', 'purchase' => 38000, 'sale' => 58000, 'stock' => 3, 'min' => 10],
            // Manettes
            ['category' => 'manettes', 'reference' => 'DS5-WHITE', 'name' => 'Manette DualSense Blanc', 'brand' => 'Sony', 'purchase' => 45000, 'sale' => 65000, 'stock' => 40, 'min' => 15],
            ['category' => 'manettes', 'reference' => 'PRO-CTRL-NS', 'name' => 'Manette Pro Nintendo Switch', 'brand' => 'Nintendo', 'purchase' => 35000, 'sale' => 52000, 'stock' => 18, 'min' => 8],
            // Casques
            ['category' => 'casques', 'reference' => 'HS-PRO-X', 'name' => 'HyperX Cloud II', 'brand' => 'HyperX', 'purchase' => 55000, 'sale' => 78000, 'stock' => 12, 'min' => 5],
            ['category' => 'casques', 'reference' => 'ARCTIS-7P', 'name' => 'SteelSeries Arctis 7P', 'brand' => 'SteelSeries', 'purchase' => 75000, 'sale' => 105000, 'stock' => 0, 'min' => 5],
            // Accessoires
            ['category' => 'accessoires', 'reference' => 'CHARGE-DOCK-PS5', 'name' => 'Station de charge DualSense', 'brand' => 'Sony', 'purchase' => 18000, 'sale' => 28000, 'stock' => 22, 'min' => 8],
            // Cartes cadeaux
            ['category' => 'cartes-cadeaux', 'reference' => 'PSN-50EUR', 'name' => 'Carte PSN 50€', 'brand' => 'Sony', 'purchase' => 32000, 'sale' => 38000, 'stock' => 50, 'min' => 20],
            ['category' => 'cartes-cadeaux', 'reference' => 'ESHOP-25EUR', 'name' => 'Carte eShop Nintendo 25€', 'brand' => 'Nintendo', 'purchase' => 16000, 'sale' => 20000, 'stock' => 35, 'min' => 15],
        ];

        foreach ($products as $data) {
            $category = Category::where('slug', $data['category'])->first();

            Product::updateOrCreate(
                ['reference' => $data['reference']],
                [
                    'category_id' => $category->id,
                    'barcode' => fake()->unique()->ean13(),
                    'name' => $data['name'],
                    'description' => "Produit gaming : {$data['name']}",
                    'brand' => $data['brand'],
                    'purchase_price' => $data['purchase'],
                    'sale_price' => $data['sale'],
                    'stock_quantity' => $data['stock'],
                    'minimum_stock' => $data['min'],
                    'is_active' => true,
                ]
            );
        }
    }
}
