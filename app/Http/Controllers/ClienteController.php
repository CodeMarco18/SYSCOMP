<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Http\Requests\ClienteRequest;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $clientes = Cliente::when($buscar, function ($query, $buscar) {
                return $query->where('nombres', 'LIKE', "%{$buscar}%")
                             ->orWhere('apellidos', 'LIKE', "%{$buscar}%")
                             ->orWhere('documento', 'LIKE', "%{$buscar}%")
                             ->orWhere('correo', 'LIKE', "%{$buscar}%");
            })
            ->orderBy('id_cliente', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('clientes.index', compact('clientes', 'buscar'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(ClienteRequest $request)
    {
        Cliente::create($request->validated());

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(ClienteRequest $request, Cliente $cliente)
    {
        $cliente->update($request->validated());

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        if ($cliente->ventas()->count() > 0) {
            $cliente->update(['estado' => 'INACTIVO']);
            return redirect()->route('clientes.index')
                ->with('info', 'El cliente registra ventas realizadas; su estado ha pasado a INACTIVO.');
        }

        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado con éxito.');
    }
}
