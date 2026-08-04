<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('cliente') ? $this->route('cliente')->id_cliente : null;

        return [
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'documento' => 'required|string|max:20|unique:clientes,documento,' . $id . ',id_cliente',
            'telefono' => 'nullable|string|max:30',
            'correo' => 'nullable|email|max:100',
            'direccion' => 'nullable|string|max:255',
            'estado' => 'required|in:ACTIVO,INACTIVO',
        ];
    }

    public function messages(): array
    {
        return [
            'nombres.required' => 'Los nombres son obligatorios.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'documento.required' => 'El documento de identidad es obligatorio.',
            'documento.unique' => 'Este número de documento ya está registrado.',
            'correo.email' => 'Ingrese un correo electrónico válido.',
        ];
    }
}
