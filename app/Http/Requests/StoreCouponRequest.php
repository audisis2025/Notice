<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreCouponRequest
 * 
 * Valida la creación de un nuevo cupón de descuento.
 *
 * @package App\Http\Requests
 */
class StoreCouponRequest extends FormRequest
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
            'code' => ['required', 'string', 'max:50', 'unique:coupons,code', 'alpha_num'],
            'discount_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'expiration_date' => ['required', 'date', 'after:today'],
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
            'code.required' => 'El código del cupón es obligatorio.',
            'code.unique' => 'Este código de cupón ya existe.',
            'code.alpha_num' => 'El código del cupón solo puede contener letras y números.',
            'discount_percentage.required' => 'El porcentaje de descuento es obligatorio.',
            'discount_percentage.numeric' => 'El porcentaje de descuento debe ser un número.',
            'discount_percentage.min' => 'El porcentaje de descuento debe ser al menos 0.',
            'discount_percentage.max' => 'El porcentaje de descuento no puede ser mayor a 100.',
            'expiration_date.required' => 'La fecha de vencimiento es obligatoria.',
            'expiration_date.date' => 'La fecha de vencimiento debe ser una fecha válida.',
            'expiration_date.after' => 'La fecha de vencimiento debe ser posterior a hoy.',
        ];
    }
}