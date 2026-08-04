<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GarantiaProducto extends Model
{
    use HasFactory;

    protected $table = 'garantia_productos';
    protected $primaryKey = 'id_garantia';

    protected $fillable = [
        'id_detalle_venta',
        'codigo_garantia',
        'fecha_inicio',
        'fecha_vencimiento',
        'periodo_meses',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_vencimiento' => 'date',
        'periodo_meses' => 'integer',
    ];

    public function detalleVenta()
    {
        return $this->belongsTo(DetalleVenta::class, 'id_detalle_venta', 'id_detalle_venta');
    }
}
