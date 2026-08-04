<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Empleado;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Http\Requests\CompraRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $compras = Compra::with(['proveedor', 'empleado'])
            ->when($buscar, function ($query, $buscar) {
                return $query->whereHas('proveedor', function ($q) use ($buscar) {
                    $q->where('razon_social', 'LIKE', "%{$buscar}%")
                      ->orWhere('ruc', 'LIKE', "%{$buscar}%");
                });
            })
            ->orderBy('id_compra', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('compras.index', compact('compras', 'buscar'));
    }

    public function create()
    {
        $proveedores = Proveedor::where('estado', 'ACTIVO')->orderBy('razon_social')->get();
        $productos = Producto::where('estado', 'ACTIVO')->orderBy('nombre_producto')->get();
        $empleados = Empleado::where('estado', 'ACTIVO')->orderBy('nombres')->get();

        return view('compras.create', compact('proveedores', 'productos', 'empleados'));
    }

    public function store(CompraRequest $request)
    {
        DB::beginTransaction();

        try {
            // Empleado que registra la compra (del usuario autenticado o seleccionado)
            $empleadoId = Auth::user()->id_empleado ?? Empleado::first()->id_empleado;

            $totalCompra = 0;
            foreach ($request->productos as $prod) {
                $totalCompra += $prod['cantidad'] * $prod['precio_unitario'];
            }

            $compra = Compra::create([
                'id_proveedor' => $request->id_proveedor,
                'id_empleado' => $empleadoId,
                'fecha' => now(),
                'subtotal' => $totalCompra,
                'total' => $totalCompra,
                'estado' => 'COMPLETADA',
            ]);

            foreach ($request->productos as $item) {
                $subtotalItem = $item['cantidad'] * $item['precio_unitario'];
                DetalleCompra::create([
                    'id_compra' => $compra->id_compra,
                    'id_producto' => $item['id_producto'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal' => $subtotalItem,
                ]);
                // Nota: El Trigger MySQL `tg_after_insert_detalle_compra` se ejecuta automáticamente 
                // aumentando el stock del producto e insertando en movimiento_inventarios (ENTRADA).
            }

            DB::commit();

            return redirect()->route('compras.index')
                ->with('success', "Compra #{$compra->id_compra} registrada e inventario actualizado correctamente.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al procesar la compra: ' . $e->getMessage());
        }
    }

    public function show(Compra $compra)
    {
        $compra->load(['proveedor', 'empleado', 'detalles.producto']);
        return view('compras.show', compact('compra'));
    }
}
