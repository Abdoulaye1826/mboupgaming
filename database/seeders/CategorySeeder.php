<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seed des catégories de produits gaming.
 */
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Consoles',
            'Jeux',
            'Manettes',
            'Casques',
            'Accessoires',
            'Cartes Cadeaux',
            'Autres',
        ];

        foreach ($categories as $name) {
            Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => "Catégorie : {$name}",
                    'is_active' => true,
                ]
            );
        }
    }
}
