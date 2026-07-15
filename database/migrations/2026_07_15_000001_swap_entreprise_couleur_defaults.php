<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Corrige l'inversion primaire/secondaire de la migration précédente :
 * primaire = couleur dominante (menu latéral, KPI principal, barre de
 * défilement) ; secondaire = accent (boutons, liens). Ajuste à la fois les
 * valeurs par défaut des colonnes et la ligne déjà en base, pour que rien
 * ne change visuellement — seul le nom du champ change de sens.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE entreprises MODIFY couleur_primaire VARCHAR(7) NOT NULL DEFAULT '#1432CA'");
        DB::statement("ALTER TABLE entreprises MODIFY couleur_secondaire VARCHAR(7) NOT NULL DEFAULT '#153BFF'");

        // Échange les valeurs déjà en base plutôt que de les écraser avec
        // les valeurs par défaut, au cas où l'entreprise les aurait déjà
        // personnalisées.
        DB::table('entreprises')->get()->each(function ($row) {
            DB::table('entreprises')->where('id', $row->id)->update([
                'couleur_primaire' => $row->couleur_secondaire,
                'couleur_secondaire' => $row->couleur_primaire,
            ]);
        });
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE entreprises MODIFY couleur_primaire VARCHAR(7) NOT NULL DEFAULT '#153BFF'");
        DB::statement("ALTER TABLE entreprises MODIFY couleur_secondaire VARCHAR(7) NOT NULL DEFAULT '#1432CA'");

        DB::table('entreprises')->get()->each(function ($row) {
            DB::table('entreprises')->where('id', $row->id)->update([
                'couleur_primaire' => $row->couleur_secondaire,
                'couleur_secondaire' => $row->couleur_primaire,
            ]);
        });
    }
};
