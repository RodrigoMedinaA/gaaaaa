<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    protected $fillable = [
        'modulo', #enum
        'nombre',
        'docente_id', #foreign key
        'modalidad', #enum
        'dias_estudio', # ¿array?
        'fecha_inicio',
        'fecha_fin',
        'turno', #enum
        'hora_inicio',
        'hora_fin',
    ];

    protected function casts(): array
    {
        return [
            'dias_estudio' => 'array',
        ];
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

    public function estudiantes()
    {
        return $this->belongsToMany(Estudiante::class, 'estudiante_seccion');
    }
}
