<?php

namespace Database\Factories;

use App\Enums\RoleSlug;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        $slug = fake()->randomElement(RoleSlug::cases());

        return [
            'name' => $slug->label(),
            'slug' => $slug->value,
            'description' => fake()->sentence(),
            'permissions' => [],
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'name' => RoleSlug::Admin->label(),
            'slug' => RoleSlug::Admin->value,
            'description' => 'Accès total au Système',
            'permissions' => ['*'],
        ]);
    }
}
