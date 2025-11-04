<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEquipoRequest extends FormRequest
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
        $equipo = $this->route('equipo');
        
        return [
            'codigo' => 'nullable|unique:productos,codigo,'.$equipo->id.'|max:50',
            'nombre' => 'required|unique:productos,nombre,'.$equipo->id.'|max:255',
            'descripcion' => 'nullable|max:255',
            'img_path' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'marca_id' => 'nullable|integer|exists:marcas,id',
            'presentacione_id' => 'required|integer|exists:presentaciones,id',
            'categoria_id' => 'nullable|integer|exists:categorias,id',
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
            'precio.required' => 'El precio es obligatorio para equipos',
            'precio.numeric' => 'El precio debe ser un número válido',
            'precio.min' => 'El precio no puede ser negativo'
        ];
    }
}