<?php

namespace App\Filament\Resources\Seccions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\ToggleButtons;

use App\Models\Modulo;
use App\Enums\Modalidad;
use App\Enums\Turno;

class SeccionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ToggleButtons::make('modulo_id')
                    ->label('Módulo')
                    ->options(Modulo::all()->pluck('nombre', 'id')) // Así se cargan las opciones
                    ->required()
                    ->live() // Reactivo al cambio
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                        // Esta lógica no cambia NADA
                        $modulo = Modulo::find($state);
                        if ($modulo) {
                            $set('costo_modulo', $modulo->costo);
                        } else {
                            $set('costo_modulo', 0);
                        }
                    })
                    ->columns(3) 
                    ->gridDirection('row'),
                TextInput::make('costo_modulo')
                    ->label('Costo')
                    ->prefix('S/.')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false)
                    ->required(false),
                TextInput::make('nombre')
                    ->required(),
                Select::make('docente_id')
                    ->relationship('docente', 'nombres')
                    ->required(),
                Select::make('modalidad')
                    ->options(Modalidad::class)
                    ->required(),
                Select::make('turno')
                    ->options(Turno::class)
                    ->required(),
                DatePicker::make('fecha_inicio')
                    ->required(),
                DatePicker::make('fecha_fin')
                    ->required(),
                TimePicker::make('hora_inicio')
                    ->required(),
                TimePicker::make('hora_fin')
                    ->required(),
                CheckboxList::make('dias_estudio')
                    ->options([
                        'Lunes' => 'Lunes',
                        'Martes' => 'Martes',
                        'Miércoles' => 'Miércoles',
                        'Jueves' => 'Jueves',
                        'Viernes' => 'Viernes',
                        'Sábado' => 'Sábado',
                    ])
                    // ->columnSpanFull()
                    ->columns(3)
                    ->required(),
            ]);
    }
}
