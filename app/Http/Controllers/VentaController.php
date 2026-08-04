<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\GarantiaProducto;
use App\Http\Requests\VentaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDO;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $ventas = Venta::with(['cliente', 'empleado'])
            ->when($buscar, function ($query, $buscar) {
                return $query->where('serie', 'LIKE', "%{$buscar}%")
                             ->orWhere('numero', 'LIKE', "%{$buscar}%")
                             ->orWhereHas('cliente', function ($q) use ($buscar) {
                                 $q->where('nombres', 'LIKE', "%{$buscar}%")
                                   ->orWhere('apellidos', 'LIKE', "%{$buscar}%")
                                   ->orWhere('documento', 'LIKE', "%{$buscar}%");
                             });
            })
            ->orderBy('id_venta', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('ventas.index', compact('ventas', 'buscar'));
    }

    public function create()
    {
        $clientes = Cliente::where('estado', 'ACTIVO')->orderBy('nombres')->get();
        $productos = Producto::where('estado', 'ACTIVO')->orderBy('nombre_producto')->get();
        $empleados = Empleado::where('estado', 'ACTIVO')->orderBy('nombres')->get();

        // Generar correlativo aleatorio o incremental de ejemplo
        $ultimaVenta = Venta::latest('id_venta')->first();
        $siguienteNumero = $ultimaVenta ? str_pad($ultimaVenta->id_venta + 1, 8, '0', STR_PAD_LEFT) : '00000001';

        return view('ventas.create', compact('clientes', 'productos', 'empleados', 'siguienteNumero'));
    }

    public function store(VentaRequest $request)
    {
        $empleadoId = Auth::user()->id_empleado ?? Empleado::first()->id_empleado;

        // Si es una venta directa de un solo item, podemos usar directamente el Procedimiento Almacenado sp_registrar_venta
        if (count($request->productos) === 1) {
            $item = $request->productos[0];

            $pdo = DB::getPdo();
            $stmt = $pdo->prepare("CALL sp_registrar_venta(:p_id_cliente, :p_id_empleado, :p_tipo_comprobante, :p_serie, :p_numero, :p_id_producto, :p_cantidad, :p_precio_unitario, @p_resultado, @p_mensaje)");

            $stmt->bindValue(':p_id_cliente', $request->id_cliente);
            $stmt->bindValue(':p_id_empleado', $empleadoId);
            $stmt->bindValue(':p_tipo_comprobante', $request->tipo_comprobante);
            $stmt->bindValue(':p_serie', $request->serie);
            $stmt->bindValue(':p_numero', $request->numero);
            $stmt->bindValue(':p_id_producto', $item['id_producto']);
            $stmt->bindValue(':p_cantidad', $item['cantidad']);
            $stmt->bindValue(':p_precio_unitario', $item['precio_unitario']);

            $stmt->execute();
            $stmt->closeCursor();

            $res = DB::select("SELECT @p_resultado AS resultado, @p_mensaje AS mensaje")[0];

            if ($res->resultado == 1) {
                return redirect()->route('ventas.index')
                    ->with('success', $res->mensaje);
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $res->mensaje);
            }
        }

        // Para ventas compuestas con múltiples productos, ejecutamos la transacción atómica con FOR UPDATE
        DB::beginTransaction();

        try {
            // 1. Verificar disponibilidad de stock con FOR UPDATE para todos los productos requeridos
            foreach ($request->productos as $item) {
                $prod = DB::table('productos')
                    ->where('id_producto', $item['id_producto'])
                    ->lockForUpdate()
                    ->first();

                if (!$prod || $prod->stock_actual < $item['cantidad']) {
                    DB::rollBack();
                    $nombreProd = $prod ? $prod->nombre_producto : 'desconocido';
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "Stock insuficiente para completar la venta del producto: {$nombreProd} (Stock actual: " . ($prod ? $prod->stock_actual : 0) . ")");
                }
            }

            // 2. Calcular total de la venta
            $totalVenta = 0;
            foreach ($request->productos as $item) {
                $totalVenta += $item['cantidad'] * $item['precio_unitario'];
            }

            // 3. Crear cabecera de la Venta
            $venta = Venta::create([
                'id_cliente' => $request->id_cliente,
                'id_empleado' => $empleadoId,
                'fecha' => now(),
                'tipo_comprobante' => $request->tipo_comprobante,
                'serie' => $request->serie,
                'numero' => $request->numero,
                'subtotal' => $totalVenta,
                'total' => $totalVenta,
                'estado' => 'COMPLETADA',
            ]);

            // 4. Crear detalles de Venta (Dispara Trigger de Descuento de Stock y Movimiento SALIDA)
            foreach ($request->productos as $item) {
                $subtotalItem = $item['cantidad'] * $item['precio_unitario'];
                $detalle = DetalleVenta::create([
                    'id_venta' => $venta->id_venta,
                    'id_producto' => $item['id_producto'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal' => $subtotalItem,
                ]);

                // Generar registro de Garantía de producto
                GarantiaProducto::create([
                    'id_detalle_venta' => $detalle->id_detalle_venta,
                    'codigo_garantia' => 'GAR-' . now()->format('Ymd') . '-' . $detalle->id_detalle_venta,
                    'fecha_inicio' => now(),
                    'fecha_vencimiento' => now()->addMonths(12),
                    'periodo_meses' => 12,
                    'estado' => 'VIGENTE',
                    'observaciones' => 'Garantía oficial por 12 meses contra defectos de fábrica.',
                ]);
            }

            DB::commit();

            return redirect()->route('ventas.index')
                ->with('success', 'Venta registrada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error inesperado. Se ejecutó ROLLBACK: ' . $e->getMessage());
        }
    }

    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'empleado', 'detalles.producto', 'detalles.garantia']);
        return view('ventas.show', compact('venta'));
    }
}
