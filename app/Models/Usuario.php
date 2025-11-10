<?php

namespace App\Models;

use App\Enums\Rol;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;   // << implementarlo
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Usuario extends Authenticatable implements FilamentUser, HasName
{
    use Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'empleado_id',
        'usuario',      // campo “username”
        'password',
        'rol',
        'email',        // opcional si luego quieres recuperación por correo
        'remember_token',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => filled($value)
                ? (Hash::needsRehash($value) ? Hash::make($value) : $value)
                : $value
        );
    }
    protected $casts = [
        'rol' => Rol::class,
        'password' => 'hashed',   // << Laravel hashea automáticamente al setear
    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->rol?->value, ['admin', 'secretaria'], true);
    }

    // Requerido por HasName para mostrar el nombre en el topbar de Filament
    public function getFilamentName(): string
    {
        return $this->empleado?->nombres
            ? ($this->empleado->nombres . ' ' . ($this->empleado->apellidos ?? ''))
            : ($this->usuario ?? 'Usuario');
    }
}
