<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use App\Http\Requests\ProductoRequest;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $categoriaId = $request->input('id_categoria');
        $estado = $request->input('estado');

        $categorias = Categoria::orderBy('nombre_categoria')->get();

        $productos = Producto::with('categoria')
            ->when($buscar, function ($query, $buscar) {
                return $query->where(function ($q) use ($buscar) {
                    $q->where('nombre_producto', 'LIKE', "%{$buscar}%")
                      ->orWhere('codigo_producto', 'LIKE', "%{$buscar}%")
                      ->orWhere('marca', 'LIKE', "%{$buscar}%")
                      ->orWhere('modelo', 'LIKE', "%{$buscar}%")
                      ->orWhere('numero_serie', 'LIKE', "%{$buscar}%");
                });
            })
            ->when($categoriaId, function ($query, $categoriaId) {
                return $query->where('id_categoria', $categoriaId);
            })
            ->when($estado, function ($query, $estado) {
                return $query->where('estado', $estado);
            })
            ->orderBy('id_producto', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('productos.index', compact('productos', 'categorias', 'buscar', 'categoriaId', 'estado'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nombre_categoria')->get();
        return view('productos.create', compact('categorias'));
    }

    public function store(ProductoRequest $request)
    {
        Producto::create($request->validated());

        return redirect()->route('productos.index')
            ->with('success', 'Producto registrado correctamente.');
    }

    public function show(Producto $producto)
    {
        $producto->load('categoria', 'movimientos');
        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::orderBy('nombre_categoria')->get();
        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(ProductoRequest $request, Producto $producto)
    {
        $producto->update($request->validated());

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        if ($producto->detalleCompras()->count() > 0 || $producto->detalleVentas()->count() > 0) {
            $producto->update(['estado' => 'INACTIVO']);
            return redirect()->route('productos.index')
                ->with('info', 'El producto tiene transacciones registradas, por lo que su estado ha sido cambiado a INACTIVO.');
        }

        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }

    public function toggleEstado(Producto $producto)
    {
        $nuevoEstado = $producto->estado === 'ACTIVO' ? 'INACTIVO' : 'ACTIVO';
        $producto->update(['estado' => $nuevoEstado]);

        return redirect()->back()->with('success', "Estado del producto actualizado a {$nuevoEstado}.");
    }
}
