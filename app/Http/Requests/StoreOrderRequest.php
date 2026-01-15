<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
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
        return [
            // ✅ SOLO order_number es requerido
            'order_number' => [
                'required',
                'string',
                'max:100',
                // Validar que sea único para este negocio
                Rule::unique('orders')->where(function ($query) {
                    return $query->where('business_id', auth()->user()->business->id);
                }),
            ],

            // ✅ Description y amount son OPCIONALES
            'description' => 'nullable|string|max:500',
            'amount' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'order_number.required' => 'El número de orden es obligatorio.',
            'order_number.unique' => 'Este número de orden ya existe en tu negocio.',
            'order_number.max' => 'El número de orden no puede exceder 100 caracteres.',
            'amount.numeric' => 'El monto debe ser un número.',
            'amount.min' => 'El monto debe ser mayor o igual a cero.',
            'description.max' => 'La descripción no puede exceder 500 caracteres.',
        ];
    }
}
