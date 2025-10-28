<?php

namespace App\Filament\Resources\Seccions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

use Filament\Forms\Components\Select;

class SeccionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('modulo')
                    ->required(),
                TextInput::make('nombre')
                    ->required(),
                Select::make('docente_id')
                    ->relationship('docente', 'nombres')
                    ->required(),
                TextInput::make('modalidad')
                    ->required(),
                TextInput::make('dias_estudio'),
                DatePicker::make('fecha_inicio')
                    ->required(),
                DatePicker::make('fecha_fin')
                    ->required(),
                TextInput::make('turno')
                    ->required(),
                TimePicker::make('hora_inicio')
                    ->required(),
                TimePicker::make('hora_fin')
                    ->required(),
            ]);
    }
}
