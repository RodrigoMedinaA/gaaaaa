<?php

namespace App\Filament\Resources\Estudiantes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Actions\Action;
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
                Section::make()
                    ->schema(static::getEstudiantesData())
                    ->columns()
                // Section::make()
                //     ->schema([
                //         Select::make('tipo_documento')
                //             ->options(TipoDocumento::class)
                //             ->required(),
                //         TextInput::make('numero_documento')
                //             ->label('Número de Documento')
                //             ->required(),
                //         DatePicker::make('fecha_nacimiento')
                //             ->label('Fecha de Nacimiento')
                //             ->required(),
                //         TextInput::make('nombre')
                //             ->label('Nombres')
                //             ->required(),
                //         TextInput::make('apellido_paterno')
                //             ->label('Apellido Paterno')
                //             ->required(),
                //         TextInput::make('apellido_materno')
                //             ->label('Apellido Materno')
                //             ->required(),
                //     ])
                //     ->columns(2),
                // Section::make('Foto')
                //     ->description('Máx. 1024 KB')
                //     ->schema([
                //         FileUpload::make('foto')
                //             ->label('Foto del Estudiante')
                //             ->image()
                //             ->maxSize(1024) // Tamaño máximo en KB
                //             ->directory('estudiantes/fotos')
                //             ->nullable(),
                //     ]),
                // Section::make('Información adicional')
                //     ->schema([
                //         Select::make('genero')
                //             ->options(TipoGenero::class),
                //         Select::make('estado_civil')
                //             ->options(EstadoCivil::class),
                //         TextInput::make('telefono')
                //             ->label('Teléfono')
                //             ->tel(),
                //         TextInput::make('email')
                //             ->label('Correo Electrónico')
                //             ->email(),
                //     ])
                //     ->collapsed(),
            ]);
    }

    /**
     * @return array<Component>
     */
    public static function getEstudiantesData(): array
    {
        return
        [
            Select::make('tipo_documento')
                ->options(TipoDocumento::class)
                ->required(),
            TextInput::make('nro_documento')
                ->label('Número de Documento')
                ->required(),
            DatePicker::make('fecha_nacimiento')
                ->label('Fecha de Nacimiento')
                ->required(),
            TextInput::make('nombres')
                ->label('Nombres')
                ->required(),
            TextInput::make('apellido_paterno')
                ->label('Apellido Paterno')
                ->required(),
            TextInput::make('apellido_materno')
                ->label('Apellido Materno')
                ->required(),
        ];
    }

    /**
     * @return array<Component>
     */
    public static function getApoderadoData(): array
    {
        return[
            Select::make('apoderado_id')
                ->relationship('apoderado', 'nombres')
                ->searchable()
                ->required()
                ->createOptionForm([
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
                ])
                ->createOptionAction(function (Action $action) {
                    return $action
                        ->modalHeading('Crear Apoderado')
                        ->modalSubmitActionLabel('Crear Apoderado')
                        ->modalWidth('lg');
                }),
        ];
    }
}
