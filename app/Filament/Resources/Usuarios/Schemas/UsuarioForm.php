<?php

namespace App\Filament\Resources\Usuarios\Schemas;

use App\Enums\Rol;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UsuarioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('empleado_id')
                    ->relationship('empleado', 'id')
                    ->required(),
                TextInput::make('usuario')
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Select::make('rol')
                    ->options(Rol::class)
                    ->required(),
            ]);
    }
}
