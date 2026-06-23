<?php

namespace App\Enums;

enum NotificationType: string
{
    case LowStock = 'low_stock';
    case OutOfStock = 'out_of_stock';
    case NewOrder = 'new_order';

    public function label(): string
    {
        return match ($this) {
            self::LowStock => 'Stock faible',
            self::OutOfStock => 'Rupture de stock',
            self::NewOrder => 'Nouvelle commande',
        };
    }
}
