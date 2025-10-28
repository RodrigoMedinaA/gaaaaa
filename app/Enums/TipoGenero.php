<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TipoGenero: string implements HasLabel
{
    case MASCULINO = 'Masculino';
    case FEMENINO = 'Femenino';
    case OTRO = 'Otro';
    case NO_DEFINIDO = 'No Definido';

    public function getLabel(): string
    {
        return $this->value;
    }  
}
