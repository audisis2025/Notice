<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'client_name' => 'nullable|string|max:255',
            'client_phone' => 'nullable|string|max:20',
            'client_email' => 'nullable|email|max:255',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*.name' => 'required|string',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0'
        ];
    }

    public function messages()
    {
        return [
            'total_amount.required' => 'El monto total es obligatorio',
            'total_amount.min' => 'El monto debe ser mayor a 0',
            'products.*.name.required' => 'El nombre del producto es obligatorio',
            'products.*.quantity.required' => 'La cantidad es obligatoria',
            'products.*.price.required' => 'El precio es obligatorio'
        ];
    }
}
