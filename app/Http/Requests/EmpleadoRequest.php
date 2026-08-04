<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpleadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('empleado') ? $this->route('empleado')->id_empleado : null;

        return [
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'documento' => 'required|string|max:20|unique:empleados,documento,' . $id . ',id_empleado',
            'cargo' => 'required|string|max:50',
            'telefono' => 'nullable|string|max:30',
            'correo' => 'nullable|email|max:100',
            'estado' => 'required|in:ACTIVO,INACTIVO',
        ];
    }

    public function messages(): array
    {
        return [
            'nombres.required' => 'Los nombres son obligatorios.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'documento.required' => 'El documento de identidad es obligatorio.',
            'documento.unique' => 'El documento ya pertenece a otro empleado.',
            'cargo.required' => 'El cargo es obligatorio.',
        ];
    }
}
