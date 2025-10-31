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
        'codigo', #foreign key
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
    ];

    protected function casts(): array
    {
        return [
            'dias_estudio' => 'array',
        ];
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
