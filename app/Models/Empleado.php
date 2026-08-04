<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';
    protected $primaryKey = 'id_empleado';

    protected $fillable = [
        'nombres',
        'apellidos',
        'documento',
        'cargo',
        'telefono',
        'correo',
        'estado',
    ];

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombres} {$this->apellidos}";
    }

    public function usuario()
    {
        return $this->hasOne(User::class, 'id_empleado', 'id_empleado');
    }

    public function compras()
    {
        return $this->hasMany(Compra::class, 'id_empleado', 'id_empleado');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_empleado', 'id_empleado');
    }
}
