<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

enum EstadoCivil: string implements HasLabel
{
    case SOLTERO = 'Soltero';
    case CASADO = 'Casado';
    case DIVORCIADO = 'Divorciado';
    case VIUDO = 'Viudo';

    public function getLabel(): string
    {
        return $this->value;
    }
}
