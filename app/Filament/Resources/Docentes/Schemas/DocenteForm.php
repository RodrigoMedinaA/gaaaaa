<?php

namespace App\Filament\Resources\Docentes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

use App\Enums\TipoDocumento;
use App\Enums\Modulo_seccion;

class DocenteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tipo_documento')
                    ->label('Tipo de Documento')
                    ->options(TipoDocumento::class)
                    ->required(),
                TextInput::make('nro_documento')
                    ->required(),
                TextInput::make('nombres')
                    ->required(),
                TextInput::make('apellido_paterno')
                    ->required(),
                TextInput::make('apellido_materno')
                    ->required(),
                Select::make('especialidad')
                    ->options(Modulo_seccion::class)
                    ->required(),
            ]);
    }
}
