<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';

    protected $fillable = [
        'nombres',
        'apellidos',
        'documento',
        'telefono',
        'correo',
        'direccion',
        'estado',
    ];

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombres} {$this->apellidos}";
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_cliente', 'id_cliente');
    }
}
