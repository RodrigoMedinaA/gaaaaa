<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Seccion;

class Docente extends Model
{
    protected $fillable = [
        'tipo_documento',
        'nro_documento',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'modulo_id', #foreign key
    ];

    public function secciones() : HasMany
    {
        return $this->hasMany(Seccion::class);
    }
 
    public function modulo() : BelongsTo
    {
        return $this->belongsTo(Modulo::class);
    }
}
