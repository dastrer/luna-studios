<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventarioRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Determinar si es servicio basado en el código del producto
        $productoId = $this->input('producto_id');
        $tipoProducto = $this->input('tipo_producto');
        
        $rules = [
            'producto_id' => 'required|exists:productos,id',
            'ubicacione_id' => 'required|exists:ubicaciones,id',
            'cantidad' => 'required|integer|min:1',
            'fecha_vencimiento' => 'nullable|date|after_or_equal:today',
            'tipo_producto' => 'nullable|string|in:servicio,equipo'
        ];

        // Reglas diferentes para servicios vs equipos
        if ($tipoProducto === 'servicio') {
            $rules['costo_unitario'] = 'required|numeric|min:0.1';
        } else {
            $rules['costo_unitario'] = 'nullable|numeric|min:0';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'producto_id.required' => 'El producto es requerido',
            'producto_id.exists' => 'El producto seleccionado no existe',
            'ubicacione_id.required' => 'La ubicación es requerida',
            'ubicacione_id.exists' => 'La ubicación seleccionada no existe',
            'cantidad.required' => 'La cantidad es requerida',
            'cantidad.integer' => 'La cantidad debe ser un número entero',
            'cantidad.min' => 'La cantidad debe ser al menos 1',
            'costo_unitario.required' => 'El costo unitario es requerido para servicios',
            'costo_unitario.numeric' => 'El costo unitario debe ser un valor numérico',
            'costo_unitario.min' => 'El costo unitario debe ser mayor a 0',
            'fecha_vencimiento.date' => 'La fecha de vencimiento debe ser una fecha válida',
            'fecha_vencimiento.after_or_equal' => 'La fecha de vencimiento no puede ser anterior a hoy'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Para equipos, asegurar que costo_unitario sea null si está vacío
        if ($this->input('tipo_producto') === 'equipo' && 
            ($this->input('costo_unitario') === '' || $this->input('costo_unitario') === null)) {
            $this->merge([
                'costo_unitario' => null
            ]);
        }

        // Para servicios, asegurar que fecha_vencimiento sea null
        if ($this->input('tipo_producto') === 'servicio') {
            $this->merge([
                'fecha_vencimiento' => null
            ]);
        }
    }
}