<?php

namespace App\Filament\Resources\Empleados\Schemas;

use App\Enums\TipoDocumento;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmpleadoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),
                TextInput::make('apellido_paterno')
                    ->required(),
                TextInput::make('apellido_materno'),
                TextInput::make('correo')
                    ->required(),
                TextInput::make('celular'),
                Select::make('tipo_documento')
                    ->options(TipoDocumento::class)
                    ->required(),
                TextInput::make('num_documento')
                    ->required(),
            ]);
    }
}
