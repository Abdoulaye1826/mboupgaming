<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

/**
 * Seed des clients de démonstration.
 */
class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['full_name' => 'Amadou Ba', 'phone' => '+221 77 111 11 01', 'email' => 'amadou.ba@email.com', 'city' => 'Dakar'],
            ['full_name' => 'Mariama Ndiaye', 'phone' => '+221 77 111 11 02', 'email' => 'mariama.ndiaye@email.com', 'city' => 'Pikine'],
            ['full_name' => 'Ousmane Sy', 'phone' => '+221 77 111 11 03', 'email' => 'ousmane.sy@email.com', 'city' => 'Thiès'],
            ['full_name' => 'Aïssatou Diallo', 'phone' => '+221 77 111 11 04', 'email' => null, 'city' => 'Rufisque'],
            ['full_name' => 'Cheikh Mbaye', 'phone' => '+221 77 111 11 05', 'email' => 'cheikh.mbaye@email.com', 'city' => 'Dakar'],
        ];

        foreach ($customers as $data) {
            Customer::updateOrCreate(
                ['phone' => $data['phone']],
                [
                    'full_name' => $data['full_name'],
                    'email' => $data['email'],
                    'address' => fake()->streetAddress(),
                    'city' => $data['city'],
                    'registered_at' => now()->subDays(rand(30, 365)),
                ]
            );
        }
    }
}
