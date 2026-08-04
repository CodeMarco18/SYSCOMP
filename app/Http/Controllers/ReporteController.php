<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $tipoReporte = $request->input('tipo_reporte', 'stock_actual');
        $fechaInicio = $request->input('fecha_inicio', now()->subDays(30)->toDateString());
        $fechaFin = $request->input('fecha_fin', now()->toDateString());
        $categoriaId = $request->input('id_categoria');
        $proveedorId = $request->input('id_proveedor');

        $categorias = Categoria::orderBy('nombre_categoria')->get();
        $proveedores = Proveedor::orderBy('razon_social')->get();

        $resultados = collect();

        switch ($tipoReporte) {
            case 'stock_actual':
                $resultados = DB::table('vw_stock_actual')
                    ->when($categoriaId, function ($query, $categoriaId) {
                        return $query->where('nombre_categoria', function($q) use ($categoriaId) {
                            $q->select('nombre_categoria')->from('categorias')->where('id_categoria', $categoriaId);
                        });
                    })
                    ->get();
                break;

            case 'stock_bajo':
                $resultados = DB::table('vw_stock_actual')
                    ->where('estado_stock', 'REPONER')
                    ->get();
                break;

            case 'baja_rotacion':
                $resultados = DB::table('vw_baja_rotacion')->get();
                break;

            case 'ventas_diarias':
                $resultados = DB::table('vw_ventas_diarias')
                    ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                    ->get();
                break;

            case 'productos_mas_vendidos':
                $resultados = DB::table('detalle_ventas as dv')
                    ->join('productos as p', 'dv.id_producto', '=', 'p.id_producto')
                    ->join('ventas as v', 'dv.id_venta', '=', 'v.id_venta')
                    ->select('p.codigo_producto', 'p.nombre_producto', DB::raw('SUM(dv.cantidad) as total_unidades'), DB::raw('SUM(dv.subtotal) as total_recaudado'))
                    ->whereBetween(DB::raw('DATE(v.fecha)'), [$fechaInicio, $fechaFin])
                    ->groupBy('p.id_producto', 'p.codigo_producto', 'p.nombre_producto')
                    ->orderByDesc('total_unidades')
                    ->get();
                break;

            case 'compras_proveedor':
                $resultados = DB::table('compras as c')
                    ->join('proveedores as pr', 'c.id_proveedor', '=', 'pr.id_proveedor')
                    ->select('pr.ruc', 'pr.razon_social', DB::raw('COUNT(c.id_compra) as total_compras'), DB::raw('SUM(c.total) as monto_total'))
                    ->whereBetween(DB::raw('DATE(c.fecha)'), [$fechaInicio, $fechaFin])
                    ->when($proveedorId, function ($query, $proveedorId) {
                        return $query->where('c.id_proveedor', $proveedorId);
                    })
                    ->groupBy('pr.id_proveedor', 'pr.ruc', 'pr.razon_social')
                    ->get();
                break;

            case 'movimientos':
                $resultados = DB::table('movimiento_inventarios as m')
                    ->join('productos as p', 'm.id_producto', '=', 'p.id_producto')
                    ->select('m.fecha', 'p.codigo_producto', 'p.nombre_producto', 'm.tipo_movimiento', 'm.cantidad', 'm.referencia', 'm.stock_resultante')
                    ->whereBetween(DB::raw('DATE(m.fecha)'), [$fechaInicio, $fechaFin])
                    ->orderBy('m.id_movimiento', 'desc')
                    ->get();
                break;
        }

        return view('reportes.index', compact(
            'tipoReporte',
            'fechaInicio',
            'fechaFin',
            'categoriaId',
            'proveedorId',
            'categorias',
            'proveedores',
            'resultados'
        ));
    }
}
