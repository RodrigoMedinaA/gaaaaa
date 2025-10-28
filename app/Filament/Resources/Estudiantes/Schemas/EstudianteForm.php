<?php

namespace App\Filament\Resources\Estudiantes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

use App\Enums\TipoDocumento;
use App\Enums\TipoGenero;
use App\Enums\EstadoCivil;

class EstudianteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tipo_documento')
                    ->label('Tipo de Documento')
                    ->options([
                        TipoDocumento::DNI->value => 'DNI',
                        TipoDocumento::CARNET_EXTRANJERIA->value => 'Carnet de Extranjería',
                    ])
                    ->required(),
                TextInput::make('nro_documento')
                    ->maxLength(20)
                    ->required(),
                TextInput::make('nombres')
                    ->required(),
                TextInput::make('apellido_paterno')
                    ->required(),
                TextInput::make('apellido_materno')
                    ->required(),
                Select::make('genero')
                    ->options(TipoGenero::class)
                    ->required(),
                Select::make('estado_civil')
                    ->options(EstadoCivil::class)
                    ->required(),
                DatePicker::make('fecha_nacimiento')
                    ->label('Fecha de Nacimiento')
                    ->required(),
                TextInput::make('telefono')
                    ->tel()
                    ->required(),
                TextInput::make('direccion'),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
            ]);
    }
}
