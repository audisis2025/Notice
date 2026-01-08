<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * ContractPackageRequest
 * 
 * Valida la contratación de un paquete por un negocio.
 *
 * @package App\Http\Requests
 */
class ContractPackageRequest extends FormRequest
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
            'package_id' => ['required', 'exists:packages,id'],
            'coupon_code' => ['nullable', 'string', 'exists:coupons,code'],
            'payment_method' => ['required', 'string', 'in:credit_card,debit_card'],
            'card_number' => ['required', 'string', 'size:16', 'regex:/^[0-9]+$/'],
            'card_holder' => ['required', 'string', 'max:255'],
            'card_expiry' => ['required', 'string', 'regex:/^(0[1-9]|1[0-2])\/[0-9]{2}$/'],
            'card_cvv' => ['required', 'string', 'size:3', 'regex:/^[0-9]+$/'],
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
            'package_id.required' => 'El paquete es obligatorio.',
            'package_id.exists' => 'El paquete seleccionado no existe.',
            'coupon_code.exists' => 'El cupón ingresado no es válido.',
            'payment_method.required' => 'El método de pago es obligatorio.',
            'payment_method.in' => 'El método de pago seleccionado no es válido.',
            'card_number.required' => 'El número de tarjeta es obligatorio.',
            'card_number.size' => 'El número de tarjeta debe tener 16 dígitos.',
            'card_number.regex' => 'El número de tarjeta solo puede contener números.',
            'card_holder.required' => 'El nombre del titular es obligatorio.',
            'card_expiry.required' => 'La fecha de vencimiento es obligatoria.',
            'card_expiry.regex' => 'La fecha de vencimiento debe tener el formato MM/AA.',
            'card_cvv.required' => 'El CVV es obligatorio.',
            'card_cvv.size' => 'El CVV debe tener 3 dígitos.',
            'card_cvv.regex' => 'El CVV solo puede contener números.',
        ];
    }
}