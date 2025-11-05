<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductoRequest extends FormRequest
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
            'codigo' => 'nullable|unique:productos,codigo|max:50',
            'nombre' => 'required|string|max:255|unique:productos,nombre',
            'descripcion' => 'nullable|string|max:255',
            'img_path' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'marca_id' => 'nullable|exists:marcas,id',
            'presentacione_id' => 'required|integer|exists:presentaciones,id',
            'categoria_id' => 'required|integer|exists:categorias,id',
            'precio' => 'nullable|numeric|min:0'
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
            'codigo.unique' => 'Este código ya está en uso',
            'nombre.unique' => 'Este nombre de producto ya está registrado',
            'nombre.required' => 'El nombre del producto es obligatorio',
            'presentacione_id.required' => 'La presentación es obligatoria',
            'presentacione_id.exists' => 'La presentación seleccionada no es válida',
            'categoria_id.required' => 'La categoría es obligatoria',
            'categoria_id.exists' => 'La categoría seleccionada no es válida',
            'marca_id.exists' => 'La marca seleccionada no es válida',
            'precio.numeric' => 'El precio debe ser un valor numérico',
            'precio.min' => 'El precio no puede ser negativo',
            'img_path.image' => 'El archivo debe ser una imagen válida',
            'img_path.mimes' => 'La imagen debe ser en formato PNG, JPG o JPEG',
            'img_path.max' => 'La imagen no debe pesar más de 2MB'
        ];
    }
}