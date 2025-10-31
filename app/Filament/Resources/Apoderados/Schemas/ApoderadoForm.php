<?php

namespace App\Filament\Resources\Apoderados\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ApoderadoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tipo_documento')
                    ->required(),
                TextInput::make('nro_documento')
                    ->required(),
                TextInput::make('nombres')
                    ->required(),
                TextInput::make('apellido_paterno')
                    ->required(),
                TextInput::make('apellido_materno')
                    ->required(),
                TextInput::make('telefono')
                    ->tel()
                    ->required(),
            ]);
    }
}
