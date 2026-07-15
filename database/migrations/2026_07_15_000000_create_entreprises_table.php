<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entreprises', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('slogan')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('adresse_ligne1')->nullable();
            $table->string('adresse_ligne2')->nullable();
            $table->string('ville')->nullable();
            $table->string('pays')->default('Sénégal');
            $table->string('ninea')->nullable();
            $table->string('rccm')->nullable();
            // Primaire = couleur dominante (menu latéral, KPI principal,
            // barre de défilement) ; secondaire = accent (boutons, liens).
            $table->string('couleur_primaire', 7)->default('#1432CA');
            $table->string('couleur_secondaire', 7)->default('#153BFF');
            $table->text('conditions_vente')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entreprises');
    }
};
