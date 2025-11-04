<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEquipoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'codigo' => 'required|unique:productos,codigo|max:50',
            'nombre' => 'required|unique:productos,nombre|max:255',
            'descripcion' => 'nullable|max:255',
            'img_path' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'marca_id' => 'nullable|integer|exists:marcas,id',
            'presentacione_id' => 'required|integer|exists:presentaciones,id',
            'categoria_id' => 'nullable',
            'precio' => 'required|numeric|min:0'
        ];
    }

    public function attributes()
    {
        return [
            'marca_id' => 'marca',
            'presentacione_id' => 'presentación',
            'categoria_id' => 'categoría',
            'precio' => 'precio'
        ];
    }
    
    public function messages()
    {
        return [
            'codigo.required' => 'El código es obligatorio para equipos',
            'codigo.unique' => 'Este código ya está en uso',
            'precio.required' => 'El precio es obligatorio para equipos',
            'precio.numeric' => 'El precio debe ser un número válido',
            'precio.min' => 'El precio no puede ser negativo'
        ];
    }
}