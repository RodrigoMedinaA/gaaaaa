<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Turno: string
{
    case MAÑANA = 'Mañana';
    case TARDE = 'Tarde';

    public function getLabel(): string
    {
        return $this->value;
    }
}
