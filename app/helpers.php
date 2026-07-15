<?php

use App\Models\Entreprise;

if (! function_exists('entreprise')) {
    /**
     * Accès global à l'entreprise courante — utilisable dans les vues,
     * les PDF DomPDF et les mails.
     */
    function entreprise(): Entreprise
    {
        return Entreprise::current();
    }
}
