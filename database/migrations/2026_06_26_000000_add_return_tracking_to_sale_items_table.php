<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration : traçabilité des retours client sur les lignes de vente.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->timestamp('returned_at')->nullable()->comment('Date du retour client, null si non retourné');
            $table->foreignId('returned_by')->nullable()->constrained('users')->nullOnDelete();

            $table->index('returned_at');
        });
    }

    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('returned_by');
            $table->dropColumn('returned_at');
        });
    }
};
