<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProveedorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('proveedore') ? $this->route('proveedore')->id_proveedor : null;

        return [
            'razon_social' => 'required|string|max:150',
            'ruc' => 'required|string|size:11|unique:proveedores,ruc,' . $id . ',id_proveedor',
            'telefono' => 'nullable|string|max:30',
            'correo' => 'nullable|email|max:100',
            'direccion' => 'nullable|string|max:255',
            'estado' => 'required|in:ACTIVO,INACTIVO',
        ];
    }

    public function messages(): array
    {
        return [
            'razon_social.required' => 'La razón social es obligatoria.',
            'ruc.required' => 'El RUC es obligatorio.',
            'ruc.size' => 'El RUC debe tener exactamente 11 dígitos.',
            'ruc.unique' => 'El RUC ingresado ya se encuentra registrado.',
            'correo.email' => 'El correo electrónico no tiene un formato válido.',
        ];
    }
}
