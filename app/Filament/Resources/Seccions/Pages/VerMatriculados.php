<?php

namespace App\Filament\Resources\Seccions\Pages;

use App\Filament\Resources\Seccions\SeccionResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

use App\Models\Matricula;
use App\Models\Estudiante;
use App\Filament\Resources\Matriculas\MatriculaResource; // Para la URL de redirección
use App\Filament\Resources\Estudiantes\EstudianteResource;
use Illuminate\Database\Eloquent\Builder;

class VerMatriculados extends Page implements HasTable
{
    use InteractsWithTable;
    use InteractsWithRecord;

    protected static string $resource = SeccionResource::class;

    protected string $view = 'filament.resources.seccions.pages.ver-matriculados';

    // public \App\Models\Seccion $record;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getTitle(): string
    {
        return 'Listado de alumnos en: ' . $this->record->nombre_solo;
    }

    public function table(Table $table): Table
    {
        return $table
            // 6. Define la consulta:
            // "Dame todas las matrículas (con sus estudiantes) 
            // donde 'seccion_id' sea el ID de la sección que estamos viendo"
            ->query(
                Matricula::query()
                    ->with('estudiante')
                    ->where('seccion_id', $this->record->id)
            )
            ->columns([
                // 7. Esta es la columna clickeable (Paso 3 de tu plan)
                TextColumn::make('estudiante.nombres')
                    ->label('Nombre del Alumno')
                    ->searchable()
                    ->url(fn (Matricula $record): string => 
                        // Redirige a la página de Edición de la Matrícula
                        EstudianteResource::getUrl('edit', ['record' => $record->estudiante_id])
                    ),
                
                // TextColumn::make('estudiante.nro_documento')
                //     ->label('Documento'),

                TextColumn::make('codigo')
                    ->label('Cód. Matrícula')
                    ->searchable()
                    ->url(fn (Matricula $record): string => 
                        // Redirige a la página de Edición de la Matrícula
                        MatriculaResource::getUrl('edit', ['record' => $record->id])
                    ),
            ])
            // 8. Como pediste, sin botones de acción
            ->actions([])
            ->headerActions([])
            ->bulkActions([]);
    }
}
