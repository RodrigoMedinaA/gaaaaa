<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Rol: string implements HasLabel
{
    case ADMIN = 'admin';
    case SECRETARIA = 'secretaria';
    

    public function getLabel(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::SECRETARIA => 'Secretaría',
            
        };
    }
}
