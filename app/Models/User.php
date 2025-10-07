<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    // Traits que agregan funcionalidades a este modelo:
    // - HasApiTokens: permite emitir y manejar tokens de acceso (Laravel Sanctum).
    // - HasFactory: habilita las factories para pruebas y seeders.
    // - Notifiable: permite enviar notificaciones (emails, etc.).
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password'];

    // Campos que deben ocultarse al convertir el modelo a JSON.
    protected $hidden = ['password', 'remember_token'];

    // Conversión de tipos de datos al leer/escribir.
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación uno a muchos con las tareas.
     * Un usuario puede tener muchas tareas.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
