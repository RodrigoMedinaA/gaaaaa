<?php

namespace App\Filament\Resources\Usuarios\Schemas;

use App\Enums\Rol;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Models\Empleado;

class UsuarioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('empleado_id')
                    ->label('Empleado')
    ->relationship('empleado', 'nombre') // base para la relación
    ->getOptionLabelFromRecordUsing(function (Empleado $e) {
        return trim($e->nombre.' '.$e->apellido_paterno.' '.$e->apellido_materno);
    })
    ->searchable()
    ->preload()
    ->required(),  
    Select::make('rol')
                    ->options(Rol::class)
                    ->required(),
                TextInput::make('usuario')
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state)) // no sobrescribe si está vacío en editar
                    ->required(fn ($record) => $record === null),
                
            ]);
    }
}
