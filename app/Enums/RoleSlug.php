<?php

namespace App\Enums;

/**
 * Identifiants techniques des rôles utilisateurs.
 */
enum RoleSlug: string
{
    case Admin = 'admin';
    case Manager = 'manager';
    case Cashier = 'cashier';
    case Driver = 'driver';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrateur',
            self::Manager => 'Gestionnaire',
            self::Cashier => 'Caissier',
            self::Driver => 'Livreur',
        };
    }
}
