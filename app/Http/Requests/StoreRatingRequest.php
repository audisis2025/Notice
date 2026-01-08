<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreRatingRequest
 * 
 * Valida la creación de una calificación por un usuario móvil.
 *
 * @package App\Http\Requests
 */
class StoreRatingRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->user()->isMobileUser();
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
            'stars' => ['required', 'integer', 'min:0', 'max:5'],
            'comment' => ['nullable', 'string', 'max:500'],
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
            'stars.required' => 'La calificación es obligatoria.',
            'stars.integer' => 'La calificación debe ser un número entero.',
            'stars.min' => 'La calificación mínima es 0 estrellas.',
            'stars.max' => 'La calificación máxima es 5 estrellas.',
            'comment.max' => 'El comentario no puede tener más de 500 caracteres.',
        ];
    }
}