<?php

namespace App\Http\Controllers;

use App\Models\GarantiaProducto;
use Illuminate\Http\Request;

class GarantiaProductoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $estado = $request->input('estado');

        // Actualizar automáticamente estado de garantías vencidas
        GarantiaProducto::where('fecha_vencimiento', '<', now()->toDateString())
            ->where('estado', 'VIGENTE')
            ->update(['estado' => 'VENCIDA']);

        $garantias = GarantiaProducto::with(['detalleVenta.venta.cliente', 'detalleVenta.producto'])
            ->when($buscar, function ($query, $buscar) {
                return $query->where('codigo_garantia', 'LIKE', "%{$buscar}%")
                             ->orWhereHas('detalleVenta.producto', function ($q) use ($buscar) {
                                 $q->where('nombre_producto', 'LIKE', "%{$buscar}%")
                                   ->orWhere('numero_serie', 'LIKE', "%{$buscar}%")
                                   ->orWhere('codigo_producto', 'LIKE', "%{$buscar}%");
                             });
            })
            ->when($estado, function ($query, $estado) {
                return $query->where('estado', $estado);
            })
            ->orderBy('id_garantia', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('garantias.index', compact('garantias', 'buscar', 'estado'));
    }

    public function show(GarantiaProducto $garantia)
    {
        $garantia->load(['detalleVenta.venta.cliente', 'detalleVenta.venta.empleado', 'detalleVenta.producto']);
        return view('garantias.show', compact('garantia'));
    }
}
