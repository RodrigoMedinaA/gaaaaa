<?php

namespace App\Filament\Resources\Matriculas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use App\Models\Estudiante;
use App\Models\Seccion;

class MatriculaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Select::make('codigo')
                //     ->required(),
                Select::make('estudiante_id')
                    ->relationship('estudiante', 'nombres')
                    ->label('Estudiante')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        
                        self::actualizarCodigoPreview($get, $set);
                    }),
                Select::make('seccion_id')
                    ->relationship('seccion', 'nombre')
                    ->label('Seccion')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        
                        self::actualizarCodigoPreview($get, $set);
                    }),
                Select::make('estado')
                    ->options([
                        'activa'=>'Activa',
                        'inactiva'=>'Inactiva / Trunca',
                        'culminada'=>'Culminada',
                    ])
                    ->required()
                    ->default('activa'),
                TextInput::make('codigo')
                    ->label('Codigo de matrícula')
                    ->required()
                    ->disabled()
                    ->dehydrated()
            ]);
    }

    private static function actualizarCodigoPreview(Get $get, Set $set): void
    {
        $estudianteId = $get('estudiante_id');
        $seccionId = $get('seccion_id');

        if (blank($estudianteId) || blank($seccionId)) {
            $set('codigo', null);
            return;
        }

        $estudiante = Estudiante::find($estudianteId);
        $seccion = Seccion::find($seccionId);

        if (!$estudiante || !$seccion || blank($seccion->codigo)) {
            $set('codigo', 'Error: Faltan datos...');
            return;
        }

        // --- LÓGICA DE CÓDIGO ACTUALIZADA ---
        $codigoSeccion = $seccion->codigo; // ej: S-3-112025-0ZAO
        $dni = $estudiante->nro_documento ?? 'SIN-DNI'; // Asegúrate que 'nro_documento' sea la columna

        $codigoPreview = "{$codigoSeccion}-{$dni}";
        // --- FIN LÓGICA DE CÓDIGO ACTUALIZADA ---
        
        $set('codigo', $codigoPreview);
    }
}
