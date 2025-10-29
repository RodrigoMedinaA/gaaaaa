<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Docente;

class Modulo extends Model
{
    $fillable = [
        'nombre',
        'descripcion',
        'costo',
    ];

    public function docentes() : BelongsToMany
    {
        return $this->belongsToMany(Docente::class, 'docente_modulo');
    }
}
