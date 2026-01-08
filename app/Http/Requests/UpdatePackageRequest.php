<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * UpdatePackageRequest
 * 
 * Valida la actualización de un paquete existente.
 *
 * @package App\Http\Requests
 */
class UpdatePackageRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->user()->isSuperAdministrator();
    }

    /**
     * Obtiene las reglas de validación que se aplican a la petición.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'has_reports' => ['boolean'],
            'has_statistics' => ['boolean'],
            'has_filters' => ['boolean'],
            'data_retention_days' => ['required', 'integer', 'min:1'],
            'max_orders' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Obtiene los mensajes de error personalizados para las reglas de validación.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del paquete es obligatorio.',
            'price.required' => 'El precio es obligatorio.',
            'duration_days.required' => 'La duración en días es obligatoria.',
        ];
    }
}