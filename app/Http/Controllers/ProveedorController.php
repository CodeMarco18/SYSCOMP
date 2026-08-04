<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Http\Requests\ProveedorRequest;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $proveedores = Proveedor::when($buscar, function ($query, $buscar) {
                return $query->where('razon_social', 'LIKE', "%{$buscar}%")
                             ->orWhere('ruc', 'LIKE', "%{$buscar}%")
                             ->orWhere('correo', 'LIKE', "%{$buscar}%");
            })
            ->orderBy('id_proveedor', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('proveedores.index', compact('proveedores', 'buscar'));
    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(ProveedorRequest $request)
    {
        Proveedor::create($request->validated());

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor registrado exitosamente.');
    }

    public function edit(Proveedor $proveedore)
    {
        $proveedor = $proveedore;
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(ProveedorRequest $request, Proveedor $proveedore)
    {
        $proveedore->update($request->validated());

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Proveedor $proveedore)
    {
        if ($proveedore->compras()->count() > 0) {
            $proveedore->update(['estado' => 'INACTIVO']);
            return redirect()->route('proveedores.index')
                ->with('info', 'El proveedor posee compras registradas; se ha cambiado su estado a INACTIVO.');
        }

        $proveedore->delete();

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor eliminado con éxito.');
    }
}
