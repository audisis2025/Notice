<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * UpdateCouponRequest
 * 
 * Valida la actualización de un cupón existente.
 *
 * @package App\Http\Requests
 */
class UpdateCouponRequest extends FormRequest
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
        $couponId = $this->route('coupon');

        return [
            'code' => ['required', 'string', 'max:50', Rule::unique('coupons', 'code')->ignore($couponId), 'alpha_num'],
            'discount_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'expiration_date' => ['required', 'date'],
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
            'discount_percentage.required' => 'El porcentaje de descuento es obligatorio.',
        ];
    }
}