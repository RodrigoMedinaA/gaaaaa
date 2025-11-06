<?php

namespace App\Filament\Resources\Seccions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\ToggleButtons;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Modulo;

class SeccionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Módulo')
                    ->description('Información autogenerada de acuerdo al módulo elegido')
                    ->icon(Heroicon::ShoppingBag)
                    ->schema([
                        ToggleButtons::make('modulo_id')
                            ->label('Módulo')
                            ->options(Modulo::all()->pluck('nombre', 'id'))
                            ->required()
                            ->live() // importante para re-renderizar los dependientes
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                // Si se limpia el módulo, limpia campos dependientes.
                                if (blank($state)) {
                                    $set('costo', null);
                                    $set('codigo', null);
                                    $set('docente_id', null);
                                    return;
                                }

                                // Actualiza costo del módulo
                                $modulo = Modulo::find($state);
                                $set('costo_modulo', $modulo?->costo ?? 0);

                                // Genera código único (MMYYYY-XXXX)
                                $fecha = now()->format('mY');
                                $aleatorio = Str::upper(Str::random(4));
                                $codigo = "{$fecha}-{$aleatorio}";
                                $set('codigo', $codigo);

                                // Reinicia el docente seleccionado al cambiar módulo
                                $set('docente_id', null);
                            })
                            ->columns(3)
                            ->gridDirection('row'),

                        TextInput::make('codigo')
                            ->label('Código de Sección')
                            ->required()
                            ->disabled()
                            ->dehydrated() // Guardar aunque esté deshabilitado
                            ->unique(table: 'seccions', column: 'codigo', ignoreRecord: true),

                        TextInput::make('costo_modulo')
                            ->label('Costo')
                            ->prefix('S/.')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false)
                            ->required(false),
                    ]),

                Section::make('Datos de sección')
                    ->schema([
                        TextInput::make('nombre')
                            ->required(),

                        // ==== SELECT DOCENTE FILTRADO POR MÓDULO ====
                        Select::make('docente_id')
                            ->label('Docente')
                            ->relationship(
                                name: 'docente',
                                // Usa 'nombres' o cambia a 'nombre_completo' si agregas el accessor en Docente
                                titleAttribute: 'nombres',
                                modifyQueryUsing: function (Builder $query, Get $get) {
                                    $moduloId = $get('modulo_id');

                                    if (blank($moduloId)) {
                                        // No listar nada si aún no hay módulo
                                        $query->whereRaw('1=0');
                                        return;
                                    }

                                    // Filtra por relación muchos-a-muchos (pivot docente_modulo)
                                    $query->whereHas('modulos', fn (Builder $q) => $q->whereKey($moduloId));
                                },
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn (Get $get) => blank($get('modulo_id')))
                            ->hint(fn (Get $get) => blank($get('modulo_id')) ? 'Primero selecciona un módulo' : null)
                            ->live(), // asegura refresco del componente
                        // ===================================================

                        Select::make('modalidad')
                            ->options(\App\Enums\Modalidad::class)
                            ->required(),

                        Select::make('turno')
                            ->options(\App\Enums\Turno::class)
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
                            ->columnSpanFull()
                            ->columns(3)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
