<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Http\Requests\EmpleadoRequest;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $empleados = Empleado::when($buscar, function ($query, $buscar) {
                return $query->where('nombres', 'LIKE', "%{$buscar}%")
                             ->orWhere('apellidos', 'LIKE', "%{$buscar}%")
                             ->orWhere('documento', 'LIKE', "%{$buscar}%")
                             ->orWhere('cargo', 'LIKE', "%{$buscar}%");
            })
            ->orderBy('id_empleado', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('empleados.index', compact('empleados', 'buscar'));
    }

    public function create()
    {
        return view('empleados.create');
    }

    public function store(EmpleadoRequest $request)
    {
        Empleado::create($request->validated());

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado registrado exitosamente.');
    }

    public function edit(Empleado $empleado)
    {
        return view('empleados.edit', compact('empleado'));
    }

    public function update(EmpleadoRequest $request, Empleado $empleado)
    {
        $empleado->update($request->validated());

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy(Empleado $empleado)
    {
        if ($empleado->ventas()->count() > 0 || $empleado->compras()->count() > 0) {
            $empleado->update(['estado' => 'INACTIVO']);
            return redirect()->route('empleados.index')
                ->with('info', 'El empleado posee historial de operaciones; su estado fue cambiado a INACTIVO.');
        }

        $empleado->delete();

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado eliminado con éxito.');
    }
}
