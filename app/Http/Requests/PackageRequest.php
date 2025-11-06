<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_period' => 'required|in:monthly,yearly',
            'reports_limit' => 'nullable|integer|min:1',
            'available_filters' => 'nullable|array',
            'data_retention_days' => 'required|integer|min:30',
            'is_active' => 'boolean'
        ];
    }

        public function messages()
    {
        return [
            'name.required' => 'El nombre del paquete es obligatorio',
            'price.required' => 'El precio es obligatorio',
            'billing_period.required' => 'El período de facturación es obligatorio',
            'billing_period.in' => 'El período debe ser mensual o anual'
        ];
    }
}
