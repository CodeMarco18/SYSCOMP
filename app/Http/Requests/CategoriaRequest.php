<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('categoria') ? $this->route('categoria')->id_categoria : null;

        return [
            'nombre_categoria' => 'required|string|max:100|unique:categorias,nombre_categoria,' . $id . ',id_categoria',
            'descripcion' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_categoria.required' => 'El nombre de la categoría es obligatorio.',
            'nombre_categoria.unique' => 'Esta categoría ya se encuentra registrada.',
            'nombre_categoria.max' => 'El nombre no debe superar los 100 caracteres.',
        ];
    }
}
