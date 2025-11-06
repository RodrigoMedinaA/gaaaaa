<?php

namespace App\Enums;

use Filament\Support\Icons\Heroicon;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasIcon;

enum EstadoMatricula: string implements HasColor, HasIcon, HasLabel
{

    case ENPROCESO = 'En proceso';
    case CULMINADO = 'Culminado';
    case INTERRUMPIDO = 'Interrumpido / Inhabilitado';
    
    public function getLabel(): string
    {
        return match ($this) {
            self::ENPROCESO => 'En proceso',
            self::CULMINADO => 'Culminado',
            self::INTERRUMPIDO => 'Interrumpido / Inhabilitado',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ENPROCESO => 'warning',
            self::CULMINADO => 'success',
            self::INTERRUMPIDO => 'danger',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::ENPROCESO => 'heroicon-m-arrow-path',
            self::CULMINADO => 'heroicon-m-check-badge',
            self::INTERRUMPIDO => 'heroicon-m-x-circle',
        };
    }
}
