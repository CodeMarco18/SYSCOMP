@extends('layouts.app')

@section('title', 'Categorías - SysComp')
@section('page_title', 'Gestión de Categorías de Cómputo')

@section('content')
<div class="card card-custom p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <form action="{{ route('categorias.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar categoría..." value="{{ $buscar }}">
            <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
            @if($buscar)
                <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary">Limpiar</a>
            @endif
        </form>

        <a href="{{ route('categorias.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Nueva Categoría
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-custom align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Categoría</th>
                    <th>Descripción</th>
                    <th>Total Productos</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categorias as $cat)
                <tr>
                    <td><strong>#{{ $cat->id_categoria }}</strong></td>
                    <td class="fw-semibold text-dark">{{ $cat->nombre_categoria }}</td>
                    <td class="text-muted">{{ $cat->descripcion ?? 'Sin descripción' }}</td>
                    <td>
                        <span class="badge bg-info text-dark rounded-pill">{{ $cat->productos_count }} productos</span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('categorias.edit', $cat) }}" class="btn btn-sm btn-outline-warning">
                            <i class="fa-solid fa-pen-to-square"></i> Editar
                        </a>
                        <form action="{{ route('categorias.destroy', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar esta categoría?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fa-solid fa-trash"></i> Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No se encontraron categorías registradas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $categorias->links() }}
    </div>
</div>
@endsection
