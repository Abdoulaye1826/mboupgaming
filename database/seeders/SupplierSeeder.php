<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

/**
 * Seed des fournisseurs de démonstration.
 */
class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'GameDistrib Sénégal',
                'phone' => '+221 33 800 00 01',
                'email' => 'contact@gamedistrib.sn',
                'address' => 'Zone Industrielle, Dakar',
                'country' => 'Sénégal',
            ],
            [
                'name' => 'PlayImport Afrique',
                'phone' => '+221 33 800 00 02',
                'email' => 'info@playimport.sn',
                'address' => 'Rue 10, Dakar Plateau',
                'country' => 'Sénégal',
            ],
            [
                'name' => 'TechGaming Europe',
                'phone' => '+33 1 40 00 00 00',
                'email' => 'export@techgaming.eu',
                'address' => '15 Rue de la Tech, Paris',
                'country' => 'France',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(
                ['email' => $supplier['email']],
                array_merge($supplier, ['is_active' => true])
            );
        }
    }
}
