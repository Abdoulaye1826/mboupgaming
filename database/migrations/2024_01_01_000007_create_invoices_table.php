<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration : factures générées automatiquement à la validation des ventes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 30)->unique()->comment('Numéro de facture unique');
            $table->foreignId('sale_id')->unique()->constrained('sales')->restrictOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->date('issued_at')->comment('Date d\'émission de la facture');
            $table->decimal('subtotal_ht', 12, 2);
            $table->decimal('tax_amount', 12, 2);
            $table->decimal('total_ttc', 12, 2);
            $table->enum('status', ['issued', 'paid', 'cancelled'])->default('issued');
            $table->string('pdf_path')->nullable()->comment('Chemin du PDF généré par DomPDF');
            $table->timestamps();

            $table->index('issued_at');
            $table->index('status');
            $table->index('customer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
