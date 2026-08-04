<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';
    protected $primaryKey = 'id_proveedor';

    protected $fillable = [
        'razon_social',
        'ruc',
        'telefono',
        'correo',
        'direccion',
        'estado',
    ];

    public function compras()
    {
        return $this->hasMany(Compra::class, 'id_proveedor', 'id_proveedor');
    }
}
