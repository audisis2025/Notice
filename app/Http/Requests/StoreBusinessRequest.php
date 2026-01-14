<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreBusinessRequest
 * 
 * Valida el registro de un nuevo negocio con todos sus datos legales.
 *
 * @package App\Http\Requests
 */
class StoreBusinessRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * ✅ AGREGAR ESTE MÉTODO
     * Prepara los datos antes de la validación.
     */
    protected function prepareForValidation()
    {
        // Convertir checkbox a boolean (si viene, es true; si no viene, es false)
        $this->merge([
            'can_be_rated' => $this->has('can_be_rated') ? true : false,
        ]);
    }

    /**
     * Obtiene las reglas de validación que se aplican a la petición.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'business_name' => ['required', 'string', 'max:255'],
            'legal_name' => ['required', 'string', 'max:255'],
            'tax_id' => ['required', 'string', 'max:50', 'unique:businesses,tax_id'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:10'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'can_be_rated' => ['boolean'],
            'delivery_period_minutes' => ['required', 'integer', 'min:5'],
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
            'business_name.required' => 'El nombre del negocio es obligatorio.',
            'legal_name.required' => 'La razón social es obligatoria.',
            'tax_id.required' => 'El RFC o identificador fiscal es obligatorio.',
            'tax_id.unique' => 'Este RFC ya está registrado.',
            'address.required' => 'La dirección es obligatoria.',
            'city.required' => 'La ciudad es obligatoria.',
            'state.required' => 'El estado es obligatorio.',
            'country.required' => 'El país es obligatorio.',
            'postal_code.required' => 'El código postal es obligatorio.',
            'phone.required' => 'El teléfono es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'website.url' => 'El sitio web debe ser una URL válida.',
            'logo.image' => 'El logo debe ser una imagen.',
            'logo.mimes' => 'El logo debe ser un archivo de tipo: jpeg, png, jpg, gif.',
            'logo.max' => 'El logo no puede ser mayor de 2MB.',
            'latitude.between' => 'La latitud debe estar entre -90 y 90.',
            'longitude.between' => 'La longitud debe estar entre -180 y 180.',
            'delivery_period_minutes.required' => 'El período de entrega es obligatorio.',
            'delivery_period_minutes.min' => 'El período de entrega debe ser de al menos 5 minutos.',
        ];
    }
}