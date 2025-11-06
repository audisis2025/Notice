<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusinessRequest extends FormRequest
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
        $businessId = $this->route('business')?->id;

        return [
            'name' => 'required|string|max:255',
            'rfc' => 'required|string|max:13|unique:businesses,rfc,' . $businessId,
            'legal_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:businesses,email,' . $businessId,
            'contact_person' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'business_hours' => 'nullable|array',
            'logo' => 'nullable|image|max:2048',
            'package_id' => 'nullable|exists:packages,id',
            'is_active' => 'boolean',
            'ratings_enabled' => 'boolean',

            // Datos del administrador (solo en creación)
            'admin_name' => 'sometimes|required|string|max:255',
            'admin_email' => 'sometimes|required|email|max:255|unique:users,email',
            'admin_password' => 'sometimes|required|string|min:8'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre del negocio es obligatorio',
            'rfc.required' => 'El RFC es obligatorio',
            'rfc.unique' => 'Este RFC ya está registrado',
            'email.unique' => 'Este email ya está registrado',
            'admin_email.unique' => 'El email del administrador ya está en uso'
        ];
    }
}
