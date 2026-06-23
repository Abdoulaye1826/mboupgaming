<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case Issued = 'issued';
    case Paid = 'paid';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Issued => 'Émise',
            self::Paid => 'Payée',
            self::Cancelled => 'Annulée',
        };
    }
}
