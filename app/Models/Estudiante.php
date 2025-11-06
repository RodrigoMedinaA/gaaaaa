<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Seccion;
use App\Models\Apoderado;

class Estudiante extends Model
{
    #protected $table = 'estudiantes';

    protected $fillable = [
        'tipo_documento', #enum
        'nro_documento',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'genero', #enum
        'estado_civil', #enum
        'fecha_nacimiento',
        'telefono',
        'direccion',
        'email',
        'apoderado_id', #foreign key
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date', // o 'immutable_date'
    ];

    public function secciones() : BelongsToMany
    {
        return $this->belongsToMany(Seccion::class, 'estudiante_seccion');
    }

    public function apoderado() : BelongsTo
    {
        return $this->belongsTo(Apoderado::class);
    }
}
