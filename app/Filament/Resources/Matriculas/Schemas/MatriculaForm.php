<?php

namespace App\Filament\Resources\Matriculas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;

use Filament\Schemas\Schema;

use Filament\Actions\Action;

use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

use App\Models\Estudiante;
use App\Models\Seccion;
use App\Enums\TipoDocumento;

use Filament\Infolists\Components\TextEntry;
use Squire\Models\Currency;
use App\Models\Matricula;

class MatriculaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos de matrícula')
                    ->schema([

                        Select::make('estudiante_id')
                            ->relationship('estudiante', 'nombres')
                            ->label('Estudiante')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                
                                self::actualizarCodigoPreview($get, $set);
                            })
                            ->createOptionForm(self::getEstudianteFormSchema())
                            ->createOptionAction(function (Action $action) {
                                return $action
                                    ->modalHeading('Crear Nuevo Estudiante')
                                    ->modalSubmitActionLabel('Crear Estudiante')
                                    ->modalWidth('lg');
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
                            ->dehydrated(),
                    ])
                    ->columnSpan(['lg' => fn (?Matricula $record) => $record === null ? 3 : 2]),
                Section::make()
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Fecha de creación')
                            ->state(fn (Matricula $record): ?string => $record->created_at?->diffForHumans()),
        
                        TextEntry::make('updated_at')
                            ->label('Fecha de actualización')
                            ->state(fn (Matricula $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Matricula $record) => $record === null),
            ])
            ->columns(3);
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

    private static function getEstudianteFormSchema(): array
    {
        // Copia los campos esenciales de tu EstudianteForm.php aquí
        return [
            Select::make('tipo_documento')->options(TipoDocumento::class)->required(),
            TextInput::make('nro_documento')->required()->maxLength(25),
            DatePicker::make('fecha_nacimiento')
                ->label('Fecha de Nacimiento')
                ->required(),
            TextInput::make('nombres')->required(),
            TextInput::make('apellido_paterno')->required(),
            TextInput::make('apellido_materno')->required(),
        ];
    }
}