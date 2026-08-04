<?php

namespace App\Http\Controllers;

use App\Models\MovimientoInventario;
use App\Models\Producto;
use Illuminate\Http\Request;

class MovimientoInventarioController extends Controller
{
    public function index(Request $request)
    {
        $productoId = $request->input('id_producto');
        $tipoMovimiento = $request->input('tipo_movimiento');
        $buscar = $request->input('buscar');

        $productos = Producto::orderBy('nombre_producto')->get();

        $movimientos = MovimientoInventario::with('producto.categoria')
            ->when($productoId, function ($query, $productoId) {
                return $query->where('id_producto', $productoId);
            })
            ->when($tipoMovimiento, function ($query, $tipoMovimiento) {
                return $query->where('tipo_movimiento', $tipoMovimiento);
            })
            ->when($buscar, function ($query, $buscar) {
                return $query->where('referencia', 'LIKE', "%{$buscar}%")
                             ->orWhereHas('producto', function ($q) use ($buscar) {
                                 $q->where('nombre_producto', 'LIKE', "%{$buscar}%")
                                   ->orWhere('codigo_producto', 'LIKE', "%{$buscar}%");
                             });
            })
            ->orderBy('id_movimiento', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('movimientos.index', compact('movimientos', 'productos', 'productoId', 'tipoMovimiento', 'buscar'));
    }
}
