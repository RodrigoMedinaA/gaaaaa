<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Modulo_Seccion: string
{
    case OFIMATICA = 'Ofimática';
    case CONFECCION = 'Confección textil';
    case ESTETICA = 'Estética personal';

    public function getLabel(): string
    {
        return $this->value;
    }

    public function getPrecio(): float
    {
        # ¿Serán precios fijos por módulo? Consultar TUSNE
        return match ($this) {
            self::OFIMATICA  => 100.00,
            self::CONFECCION => 100.00,
            self::ESTETICA   => 85.00,
        };
    }

    public static function getPrecioFromValue(string $value): ?float
    {
        $modulo = self::tryFrom($value);
        return $modulo?->getPrecio();
    }
}
