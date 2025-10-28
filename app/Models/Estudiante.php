<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    #protected $table = 'estudiantes';

    protected $fillable = [
        'tipo_documento',
        'nro_documento',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'genero',
        'estado_civil',
        'fecha_nacimiento',
        'telefono',
        'direccion',
        'email',
    ];

    public function secciones()
    {
        return $this->belongsToMany(Seccion::class, 'estudiante_seccion');
    }
}
