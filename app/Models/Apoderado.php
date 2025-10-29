<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Estudiante;

class Apoderado extends Model
{
    protected $fillable = [
        'tipo_documento',
        'nro_documento',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'telefono',
        'email',
    ];

    public function estudiantes() : HasMany
    {
        return $this->hasMany(Estudiante::class);
    }
}
