<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration : catalogue produits de la boutique gaming.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->string('reference', 50)->unique()->comment('Référence interne unique');
            $table->string('barcode', 50)->nullable()->unique()->comment('Code-barres EAN/UPC');
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->string('brand', 100)->nullable()->comment('Marque : Sony, Nintendo, Microsoft, etc.');
            $table->decimal('purchase_price', 12, 2)->default(0)->comment('Prix d\'achat HT');
            $table->decimal('sale_price', 12, 2)->default(0)->comment('Prix de vente HT');
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->unsignedInteger('minimum_stock')->default(5)->comment('Seuil d\'alerte stock faible');
            $table->string('image')->nullable()->comment('Chemin vers l\'image du produit');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('category_id');
            $table->index('name');
            $table->index('brand');
            $table->index('is_active');
            $table->index(['stock_quantity', 'minimum_stock']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
