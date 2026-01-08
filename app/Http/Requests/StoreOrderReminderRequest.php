<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreOrderReminderRequest
 * 
 * Valida la programación de un recordatorio para una orden.
 *
 * @package App\Http\Requests
 */
class StoreOrderReminderRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->user()->isBusinessAdministrator();
    }

    /**
     * Obtiene las reglas de validación que se aplican a la petición.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'exists:orders,id'],
            'reminder_minutes' => ['required', 'integer', 'min:1'],
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
            'order_id.required' => 'La orden es obligatoria.',
            'order_id.exists' => 'La orden seleccionada no existe.',
            'reminder_minutes.required' => 'Los minutos del recordatorio son obligatorios.',
            'reminder_minutes.integer' => 'Los minutos deben ser un número entero.',
            'reminder_minutes.min' => 'El recordatorio debe ser de al menos 1 minuto.',
        ];
    }
}