<?php

namespace App\Filament\Resources\Matriculas\Pages;

use App\Filament\Resources\Matriculas\MatriculaResource;
use Filament\Resources\Pages\CreateRecord;

use App\Models\Pago;
use Carbon\CarbonPeriod;

class CreateMatricula extends CreateRecord
{
    protected static string $resource = MatriculaResource::class;

    protected function afterCreate(): void
    {
        // 1. Obtenemos la matrícula que acabamos de crear
        $matricula = $this->record;

        // 2. Obtenemos la sección y sus datos
        $seccion = $matricula->seccion;
        $monto = $seccion->modulo->costo ?? 0;
        
        // 3. Verificamos que las fechas existan
        if (is_null($seccion->fecha_inicio) || is_null($seccion->fecha_fin)) {
            // No hacer nada si la sección no tiene fechas
            return; 
        }

        // 4. Calculamos el período (esto maneja tu ejemplo de "Abril a Julio")
        // Se crea un iterador que avanza 1 mes a la vez
        $periodo = CarbonPeriod::create($seccion->fecha_inicio, '1 month', $seccion->fecha_fin);

        // 5. Creamos un pago por cada mes en el período
        foreach ($periodo as $fechaVencimiento) {
            Pago::create([
                'matricula_id' => $matricula->id,
                'monto' => $monto,
                'fecha_vencimiento_cuota' => $fechaVencimiento, // Asigna la fecha del mes
                'estado' => 'pendiente', // Por defecto 'pendiente'
            ]);
        }
    }
}
