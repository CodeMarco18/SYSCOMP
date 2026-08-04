<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('producto') ? $this->route('producto')->id_producto : null;

        return [
            'codigo_producto' => 'required|string|max:50|unique:productos,codigo_producto,' . $id . ',id_producto',
            'nombre_producto' => 'required|string|max:150',
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'marca' => 'nullable|string|max:100',
            'modelo' => 'nullable|string|max:100',
            'numero_serie' => 'nullable|string|max:100',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0|gte:precio_compra',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'estado' => 'required|in:ACTIVO,INACTIVO',
        ];
    }

    public function messages(): array
    {
        return [
            'codigo_producto.required' => 'El código del producto es obligatorio.',
            'codigo_producto.unique' => 'El código de producto ya existe.',
            'nombre_producto.required' => 'El nombre del producto es obligatorio.',
            'id_categoria.required' => 'Debe seleccionar una categoría válida.',
            'id_categoria.exists' => 'La categoría seleccionada no existe.',
            'precio_compra.required' => 'El precio de compra es obligatorio.',
            'precio_compra.min' => 'El precio de compra no puede ser negativo.',
            'precio_venta.required' => 'El precio de venta es obligatorio.',
            'precio_venta.gte' => 'El precio de venta debe ser mayor o igual al precio de compra.',
            'stock_actual.min' => 'El stock actual no puede ser negativo.',
            'stock_minimo.min' => 'El stock mínimo no puede ser negativo.',
        ];
    }
}
