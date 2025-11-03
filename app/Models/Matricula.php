<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Estudiante;
use App\Models\Seccion;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    protected $fillable = [
        // 'codigo',
        'estudiante_id',
        'seccion_id',
        'estado',
    ];

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class);
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    protected static function booted(): void
    {
        // Se ejecuta ANTES de que la matrícula se guarde
        static::creating(function (Matricula $matricula) {
            
            // 1. Obtenemos la Seccion
            $seccion = Seccion::find($matricula->seccion_id);
            // Obtenemos el código completo de la sección (ej: S-3-112025-0ZAO)
            $codigoSeccion = $seccion->codigo ?? 'SIN-SECCION-CODIGO';

            // 2. Obtenemos el Estudiante
            $estudiante = Estudiante::find($matricula->estudiante_id);
            $dni = $estudiante->nro_documento ?? 'SIN-DNI'; // Asegúrate que 'nro_documento' sea la columna

            // 3. Creamos el código de la matrícula
            // Ya no necesitamos un bucle porque la combinación CODIGO_SECCION-DNI
            // es única por definición de la validación DB (estudiante_id + seccion_id)
            $matricula->codigo = "{$codigoSeccion}-{$dni}";
        });
    }
}
