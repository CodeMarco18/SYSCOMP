<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    use HasFactory;

    protected $table = 'movimiento_inventarios';
    protected $primaryKey = 'id_movimiento';

    protected $fillable = [
        'id_producto',
        'tipo_movimiento',
        'cantidad',
        'referencia',
        'tipo_referencia',
        'stock_resultante',
        'fecha',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'stock_resultante' => 'integer',
        'fecha' => 'datetime',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}
