<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * SuspendBusinessRequest
 * 
 * Valida la suspensión de servicio de un negocio.
 *
 * @package App\Http\Requests
 */
class SuspendBusinessRequest extends FormRequest
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
            'business_id' => ['required', 'exists:businesses,id'],
            'reason' => ['nullable', 'string', 'max:500'],
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
            'business_id.required' => 'El negocio es obligatorio.',
            'business_id.exists' => 'El negocio seleccionado no existe.',
            'reason.max' => 'El motivo no puede tener más de 500 caracteres.',
        ];
    }
}