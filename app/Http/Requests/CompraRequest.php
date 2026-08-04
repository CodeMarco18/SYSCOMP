<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_proveedor' => 'required|exists:proveedores,id_proveedor',
            'productos' => 'required|array|min:1',
            'productos.*.id_producto' => 'required|exists:productos,id_producto',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0.01',
        ];
    }

    public function messages(): array
    {
        return [
            'id_proveedor.required' => 'Debe seleccionar un proveedor.',
            'id_proveedor.exists' => 'El proveedor seleccionado no existe.',
            'productos.required' => 'Debe agregar al menos un producto a la compra.',
            'productos.min' => 'Debe agregar al menos un producto a la compra.',
            'productos.*.cantidad.min' => 'La cantidad debe ser al menos 1.',
            'productos.*.precio_unitario.min' => 'El precio unitario debe ser mayor a 0.',
        ];
    }
}
