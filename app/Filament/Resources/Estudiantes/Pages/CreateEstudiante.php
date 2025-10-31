<?php

namespace App\Filament\Resources\Estudiantes\Pages;
use App\Filament\Resources\Estudiantes\Schemas\EstudianteForm;
use App\Filament\Resources\Estudiantes\EstudianteResource;
use Filament\Resources\Pages\CreateRecord;

use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Filament\Schemas\Components\Wizard\Step;

use Filament\Schemas\Components\Section;

use App\Models\Estudiante;
use App\Models\Apoderado;

class CreateEstudiante extends CreateRecord
{
    use HasWizard;

    protected static string $resource = EstudianteResource::class;

    protected function getSteps() : array
    {
        return[
            Step::make('Datos del estudiante')
                ->schema([
                    Section::make()
                        ->schema(EstudianteForm::getEstudiantesData())
                        ->columns(),
                ]),
            Step::make('Datos del apoderado')
                ->schema([
                    Section::make()
                        ->schema(EstudianteForm::getApoderadoData())
                ]),
        ];
    }
}
