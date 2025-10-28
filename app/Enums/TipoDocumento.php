<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

enum TipoDocumento: string implements HasLabel
{
    case DNI = 'DNI';
    case CARNET_EXTRANJERIA = 'Carnet de extranjeria';

    public function getLabel(): string
    {
        return $this->value;
    }
}
