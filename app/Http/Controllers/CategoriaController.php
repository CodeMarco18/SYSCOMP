<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Http\Requests\CategoriaRequest;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $categorias = Categoria::withCount('productos')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre_categoria', 'LIKE', "%{$buscar}%")
                             ->orWhere('descripcion', 'LIKE', "%{$buscar}%");
            })
            ->orderBy('id_categoria', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('categorias.index', compact('categorias', 'buscar'));
    }

    public function create()
    {
        return view('categorias.create');
    }

    public function store(CategoriaRequest $request)
    {
        Categoria::create($request->validated());

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría creada con éxito.');
    }

    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    public function update(CategoriaRequest $request, Categoria $categoria)
    {
        $categoria->update($request->validated());

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría actualizada con éxito.');
    }

    public function destroy(Categoria $categoria)
    {
        if ($categoria->productos()->count() > 0) {
            return redirect()->route('categorias.index')
                ->with('error', 'No se puede eliminar la categoría porque tiene productos asociados.');
        }

        $categoria->delete();

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}
