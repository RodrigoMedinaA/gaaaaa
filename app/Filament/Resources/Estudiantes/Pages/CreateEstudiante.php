<?php

namespace App\Filament\Resources\Estudiantes\Pages;

use App\Filament\Resources\Estudiantes\EstudianteResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

use App\Models\Estudiante;
use App\Models\Apoderado; // <-- 1. Importa el modelo Apoderado

class CreateEstudiante extends CreateRecord
{
    // use HasWizard;

    protected static string $resource = EstudianteResource::class;
}
