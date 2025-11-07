<?php

namespace App\Models;

use App\Enums\TipoDocumento;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Empleado extends Model
{
    protected $table = 'empleados';

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'correo',
        'celular',
        'tipo_documento',
        'num_documento',
    ];

    protected $casts = [
        'tipo_documento' => TipoDocumento::class,
    ];

    public function usuario(): HasOne
    {
        return $this->hasOne(Usuario::class);
    }
}
