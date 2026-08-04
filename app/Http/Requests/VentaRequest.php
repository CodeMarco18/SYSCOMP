<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'tipo_comprobante' => 'required|in:BOLETA,FACTURA,TICKET',
            'serie' => 'required|string|max:10',
            'numero' => 'required|string|max:20',
            'productos' => 'required|array|min:1',
            'productos.*.id_producto' => 'required|exists:productos,id_producto',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0.01',
        ];
    }

    public function messages(): array
    {
        return [
            'id_cliente.required' => 'Debe seleccionar un cliente para efectuar la venta.',
            'tipo_comprobante.required' => 'Seleccione el tipo de comprobante.',
            'serie.required' => 'Ingrese la serie del comprobante.',
            'numero.required' => 'Ingrese el número del comprobante.',
            'productos.required' => 'Debe agregar al menos un producto al carrito de venta.',
            'productos.min' => 'El carrito de venta debe contener al menos un producto.',
            'productos.*.cantidad.min' => 'La cantidad ingresada debe ser al menos 1.',
        ];
    }
}
