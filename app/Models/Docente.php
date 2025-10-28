<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Seccion;

class Docente extends Model
{
    protected $fillable = [
        'tipo_documento',
        'nro_documento',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'especialidad', #enum
    ];

    public function secciones() : HasMany
    {
        return $this->hasMany(Seccion::class);
    }
}
