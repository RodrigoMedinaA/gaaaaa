<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Docente;
use App\Models\Estudiante;
use App\Models\Modulo;

class Seccion extends Model
{
    protected $fillable = [
        'codigo',
        'nombre',
        'docente_id', #foreign key
        'modalidad', #enum
        'dias_estudio', # ¿array?
        'fecha_inicio',
        'fecha_fin',
        'turno', #enum
        'hora_inicio',
        'hora_fin',
        'modulo_id', #foreign key
    ];

    protected $casts = [
        'modalidad' => \App\Enums\Modalidad::class,
        'turno' => \App\Enums\Turno::class,
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'dias_estudio' => 'array',
    ];

    // Se muestra en el listado de secciones al crear Matricula
    public function getNombreCompletoAttribute(): string
    {
        // 1. Obtiene los días (ahora sí es un array)
        $dias = is_array($this->dias_estudio) ? implode(', ', $this->dias_estudio) : '';
        
        // 2. Obtiene la hora
        $hora = $this->hora_inicio ?? '';
        
        // 3. Devuelve el nombre completo
        return "{$this->nombre} ({$dias} / {$hora})";
    }

    // Se muestra de titulo al momento de listar los alumnos de una Seccion
    public function getNombreSoloAttribute(): string
    {
        // 3. Devuelve el nombre completo
        return "{$this->nombre}";
    }

    public function docente() : BelongsTo
    {
        return $this->belongsTo(Docente::class);
    }

    public function estudiantes() : BelongsToMany
    {
        return $this->belongsToMany(Estudiante::class, 'estudiante_seccion');
    }

    public function modulo() : BelongsTo
    {
        return $this->belongsTo(Modulo::class);
    }
}
