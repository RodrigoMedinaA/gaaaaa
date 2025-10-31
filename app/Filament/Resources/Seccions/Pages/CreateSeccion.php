<?php

namespace App\Filament\Resources\Seccions\Pages;

use App\Filament\Resources\Seccions\SeccionResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Seccion;
use Illuminate\Support\Str;

class CreateSeccion extends CreateRecord
{
    protected static string $resource = SeccionResource::class;

    /**
     * Se ejecuta ANTES de que los datos se guarden en la BD.
     * Aquí verificamos la unicidad del código.
     */
    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     $codigo = $data['codigo'];

    //     // Bucle "do-while" para garantizar la unicidad
    //     // Si el código ya existe, regenera la parte aleatoria y vuelve a comprobar.
    //     while (Seccion::where('codigo', $codigo)->exists()) {
            
    //         // "S-{$moduloId}-{$fecha}-{$aleatorio}";
    //         // Partimos el código para regenerar solo la parte XXXX
    //         $partes = explode('-', $codigo);
            
    //         // Regeneramos la última parte (el aleatorio)
    //         $partes[3] = Str::upper(Str::random(4));
            
    //         // Volvemos a unir el código
    //         $codigo = implode('-', $partes);
    //     }
        
    //     // Asignamos el código final (ya sea el original o el regenerado)
    //     $data['codigo'] = $codigo;

    //     return $data;
    // }
}
