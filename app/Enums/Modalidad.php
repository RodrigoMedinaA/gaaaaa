<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Modalidad: string
{
    case PRESENCIAL = 'Presencial';
    case VIRTUAL = 'Virtual';
    case SEMIPRESENCIAL = 'Semipresencial';

    public function getLabel(): string
    {
        return match ($this) {
            self::PRESENCIAL => 'Presencial',
            self::VIRTUAL => 'Virtual',
            self::SEMIPRESENCIAL => 'Semipresencial',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PRESENCIAL => 'success',
            self::VIRTUAL => 'primary',
            self::SEMIPRESENCIAL => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::PRESENCIAL => 'heroicon-o-academic-cap',
            self::VIRTUAL => 'heroicon-o-computer-desktop',
            self::SEMIPRESENCIAL => 'heroicon-o-book-open',
        };
    }
}
