<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration : mouvements de stock (entrées, sorties, ajustements, ventes, retours).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete()->comment('Utilisateur ayant effectué le mouvement');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete()->comment('Fournisseur pour les entrées stock');
            $table->enum('type', [
                'entry',
                'exit',
                'adjustment',
                'sale',
                'return',
            ])->comment('entry=entrée, exit=sortie, adjustment=inventaire, sale=vente, return=annulation');
            $table->unsignedInteger('quantity')->comment('Quantité du mouvement (toujours positive)');
            $table->unsignedInteger('quantity_before')->comment('Stock avant le mouvement');
            $table->unsignedInteger('quantity_after')->comment('Stock après le mouvement');
            $table->string('reason', 255)->nullable()->comment('Motif du mouvement');
            $table->string('reference', 50)->nullable()->comment('Référence externe (n° vente, bon de commande…)');
            $table->timestamps();

            $table->index('product_id');
            $table->index('user_id');
            $table->index('supplier_id');
            $table->index('type');
            $table->index('created_at');
            $table->index(['product_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
