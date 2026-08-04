<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'id_empleado',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }

    public function esAdmin()
    {
        return $this->role === 'Administrador';
    }

    public function esVendedor()
    {
        return $this->role === 'Vendedor' || $this->role === 'Administrador';
    }

    public function esAlmacenero()
    {
        return $this->role === 'Almacenero' || $this->role === 'Administrador';
    }
}
