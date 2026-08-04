<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProductos = Producto::where('estado', 'ACTIVO')->count();
        $totalCategorias = Categoria::count();
        $totalClientes = Cliente::where('estado', 'ACTIVO')->count();
        $totalProveedores = Proveedor::where('estado', 'ACTIVO')->count();

        $ventasHoy = Venta::whereDate('fecha', now()->today())
            ->where('estado', 'COMPLETADA')
            ->sum('total');

        $ventasMes = Venta::whereYear('fecha', now()->year)
            ->whereMonth('fecha', now()->month)
            ->where('estado', 'COMPLETADA')
            ->sum('total');

        // Productos con stock bajo
        $stockBajoCount = DB::table('vw_stock_actual')
            ->where('estado_stock', 'REPONER')
            ->count();

        // Productos con baja rotación (de la vista SQL)
        $bajaRotacionCount = DB::table('vw_baja_rotacion')->count();

        // Gráfico 1: Ventas por día (últimos 7 días)
        $ventasPorDia = DB::table('vw_ventas_diarias')
            ->limit(7)
            ->get()
            ->reverse()
            ->values();

        // Gráfico 2: Ventas Mensuales (Año Actual)
        $ventasMensuales = Venta::select(
                DB::raw('MONTH(fecha) as mes'),
                DB::raw('SUM(total) as total')
            )
            ->whereYear('fecha', now()->year)
            ->where('estado', 'COMPLETADA')
            ->groupBy(DB::raw('MONTH(fecha)'))
            ->pluck('total', 'mes')
            ->toArray();

        $mesesData = [];
        $nombresMeses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        for ($m = 1; $m <= 12; $m++) {
            $mesesData[] = [
                'mes' => $nombresMeses[$m - 1],
                'total' => $ventasMensuales[$m] ?? 0,
            ];
        }

        // Gráfico 3: Productos más vendidos
        $productosMasVendidos = DB::table('detalle_ventas as dv')
            ->join('productos as p', 'dv.id_producto', '=', 'p.id_producto')
            ->select('p.nombre_producto', DB::raw('SUM(dv.cantidad) as total_vendido'))
            ->groupBy('p.id_producto', 'p.nombre_producto')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        // Gráfico 4: Stock por categoría
        $stockPorCategoria = Categoria::withCount(['productos as stock_total' => function ($query) {
            $query->select(DB::raw('SUM(stock_actual)'));
        }])->get();

        return view('dashboard.index', compact(
            'totalProductos',
            'totalCategorias',
            'totalClientes',
            'totalProveedores',
            'ventasHoy',
            'ventasMes',
            'stockBajoCount',
            'bajaRotacionCount',
            'ventasPorDia',
            'mesesData',
            'productosMasVendidos',
            'stockPorCategoria'
        ));
    }
}
