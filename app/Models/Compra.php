<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $table = 'compras';
    protected $primaryKey = 'id_compra';

    protected $fillable = [
        'id_proveedor',
        'id_empleado',
        'fecha',
        'subtotal',
        'total',
        'estado',
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCompra::class, 'id_compra', 'id_compra');
    }
}
