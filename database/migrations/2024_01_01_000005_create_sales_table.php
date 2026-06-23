<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration : ventes (transactions commerciales).
 * Statuts : draft (brouillon), validated (validée), cancelled (annulée).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('sale_number', 30)->unique()->comment('Numéro de vente auto-généré');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete()->comment('Caissier / utilisateur ayant créé la vente');
            $table->date('sale_date');
            $table->decimal('discount_amount', 12, 2)->default(0)->comment('Remise globale en montant');
            $table->decimal('tax_rate', 5, 2)->default(18.00)->comment('Taux TVA en pourcentage');
            $table->decimal('subtotal_ht', 12, 2)->default(0)->comment('Total hors taxes');
            $table->decimal('tax_amount', 12, 2)->default(0)->comment('Montant TVA');
            $table->decimal('total_ttc', 12, 2)->default(0)->comment('Total toutes taxes comprises');
            $table->enum('status', ['draft', 'validated', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('sale_date');
            $table->index('status');
            $table->index('customer_id');
            $table->index('user_id');
            $table->index(['sale_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
