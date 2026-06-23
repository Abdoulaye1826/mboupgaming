<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration : table des rôles utilisateurs.
 * Rôles : administrateur, gestionnaire, caissier, livreur.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique()->comment('Nom affiché du rôle');
            $table->string('slug', 50)->unique()->comment('Identifiant technique (admin, manager, cashier, driver)');
            $table->text('description')->nullable();
            $table->json('permissions')->nullable()->comment('Permissions sérialisées en JSON');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
