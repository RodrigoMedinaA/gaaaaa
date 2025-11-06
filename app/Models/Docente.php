<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\Seccion;
use App\Models\Modulo;


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
 
    public function modulos() : BelongsToMany # el nombre de la clase, es el nombre de la tabla relacionada
    {
        return $this->belongsToMany(Modulo::class, 'docente_modulo');
    }
    // Accessor opcional para mostrar nombre completo en selects/listas
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombres} {$this->apellido_paterno} {$this->apellido_materno}");
    }
    
}
