<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'matricula_id',
        'monto',
        'estado',
        'fecha_vencimiento', # A editar
        'metodo_pago',
        'fecha_pago',
    ];

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Pago $pago) {
            // 1. Obtenemos la matrícula
            $matricula = $pago->matricula;
            $codigoMatricula = $matricula->codigo; // ej: S-3-112025-0ZAO-71234567

            // 2. NN (Número de Pago)
            $conteoPagos = Pago::where('matricula_id', $pago->matricula_id)->count();
            $numeroPago = str_pad($conteoPagos + 1, 2, '0', STR_PAD_LEFT); // ej: 01

            // 3. Unimos el código final
            $pago->codigo = "{$codigoMatricula}-{$numeroPago}";
        });
    }
}
