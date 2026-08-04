<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';
    protected $primaryKey = 'id_producto';

    protected $fillable = [
        'codigo_producto',
        'nombre_producto',
        'id_categoria',
        'marca',
        'modelo',
        'numero_serie',
        'precio_compra',
        'precio_venta',
        'stock_actual',
        'stock_minimo',
        'fecha_registro',
        'estado',
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'stock_actual' => 'integer',
        'stock_minimo' => 'integer',
        'fecha_registro' => 'datetime',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    public function detalleCompras()
    {
        return $this->hasMany(DetalleCompra::class, 'id_producto', 'id_producto');
    }

    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class, 'id_producto', 'id_producto');
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class, 'id_producto', 'id_producto');
    }
}
