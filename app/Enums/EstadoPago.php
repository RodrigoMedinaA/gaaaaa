<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;

enum EstadoPago: string implements HasColor, HasIcon, HasLabel
{
    case PENDIENTE = 'pendiente';
    case PAGADO = 'pagado';
    // case ANULADO = 'anulado'; // Opcional, si lo necesitas

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDIENTE => 'Pendiente',
            self::PAGADO => 'Pagado',
            // self::ANULADO => 'Anulado',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDIENTE => 'warning', // Amarillo
            self::PAGADO => 'success',   // Verde
            // self::ANULADO => 'danger',    // Rojo
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::PENDIENTE => 'heroicon-o-clock',
            self::PAGADO => 'heroicon-o-check-circle',
            // self::ANULADO => 'heroicon-o-x-circle',
        };
    }
}
