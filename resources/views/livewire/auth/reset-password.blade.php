{{--
/**
 * Nombre de la vista           : reset-password.blade.php
 * Descripción de la vista      : Vista para restablecer contraseña, permite al usuario ingresar 
 *                                nueva contraseña después de recibir enlace
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 2.0
 * Fecha de mantenimiento       : 15/01/2026
 * Folio de mantenimiento       : 
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Integración con sistema global de SweetAlert2
 * Responsable                  : Sistema
 * Revisor                      : 
 */
--}}

<x-layouts.auth>
    {{-- Componente de mensajes flash --}}
    <x-flash-messages />

    <style>
        .flex.h-9.w-9:has(svg),
        .flex.h-9.w-9:has(x-app-logo-icon),
        x-app-logo-icon {
            display: none !important;
        }
        
        [data-flux-error],
        .text-red-500,
        .text-red-600,
        flux-error,
        div:has(> .text-red-500),
        div:has(> .text-red-600) {
            display: none !important;
        }
    </style>

    <div class="flex flex-col gap-6">
        <x-auth-header 
            :title="__('Restablecer contraseña')" 
            :description="__('Por favor, ingresa tu nueva contraseña a continuación')" 
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-6" id="resetPasswordForm">
            @csrf
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <flux:input
                name="email"
                id="email"
                value="{{ request('email') }}"
                :label="__('Correo electrónico')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@gmail.com"
            />

            <flux:input
                name="password"
                id="password"
                :label="__('Contraseña')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Contraseña')"
                viewable
            />

            <flux:input
                name="password_confirmation"
                id="password_confirmation"
                :label="__('Confirmar contraseña')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirmar contraseña')"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button 
                    type="submit" 
                    variant="primary" 
                    class="w-full bg-black hover:bg-gray-900 text-white rounded-lg" 
                    data-test="reset-password-button"
                    icon="arrow-path"
                    icon-variant="outline"
                >
                    {{ __('Restablecer contraseña') }}
                </flux:button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('resetPasswordForm');
            
            // ============================================
            // VALIDACIÓN DEL FORMULARIO
            // ============================================
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Limpiar bordes de error previos
                    document.querySelectorAll('input').forEach(input => {
                        input.style.borderColor = '';
                    });
                    
                    const password = document.getElementById('password').value;
                    const passwordConfirmation = document.getElementById('password_confirmation').value;
                    
                    // Validar longitud de contraseña
                    if (password.length < 8) {
                        e.preventDefault();
                        const passwordField = document.getElementById('password');
                        passwordField.style.borderColor = '#dc2626';
                        passwordField.style.borderWidth = '2px';
                        
                        showError('La contraseña debe tener al menos 8 caracteres.', 'Contraseña muy corta');
                        passwordField.focus();
                        return false;
                    }
                    
                    // Validar que las contraseñas coincidan
                    if (password !== passwordConfirmation) {
                        e.preventDefault();
                        const passwordField = document.getElementById('password');
                        const confirmField = document.getElementById('password_confirmation');
                        
                        passwordField.style.borderColor = '#dc2626';
                        passwordField.style.borderWidth = '2px';
                        confirmField.style.borderColor = '#dc2626';
                        confirmField.style.borderWidth = '2px';
                        
                        showError('Por favor, asegúrate de que ambas contraseñas sean iguales.', 'Las contraseñas no coinciden');
                        confirmField.focus();
                        return false;
                    }
                });
            }
            
            // ============================================
            // ERRORES DE VALIDACIÓN
            // ============================================
            @if ($errors->any())
                const errores = @json($errors->all());
                
                // Traducciones de errores comunes
                const traducciones = {
                    'The password field must be at least 8 characters.': 'La contraseña debe tener al menos 8 caracteres.',
                    'The password field confirmation does not match.': 'La confirmación de contraseña no coincide.',
                    'The email field is required.': 'El campo correo electrónico es obligatorio.',
                    'The password field is required.': 'El campo contraseña es obligatorio.',
                    'This password reset token is invalid.': 'Este enlace de restablecimiento de contraseña es inválido.',
                    'passwords.token': 'Este enlace de restablecimiento de contraseña ha expirado.'
                };
                
                // Un solo error
                if (errores.length === 1) {
                    const errorTraducido = traducciones[errores[0]] || errores[0];
                    showError(errorTraducido, 'Error al restablecer contraseña');
                } 
                // Múltiples errores
                else {
                    let mensajeHtml = '<div style="text-align: left; padding-left: 1rem;">';
                    mensajeHtml += '<ul style="margin: 0; padding-left: 1.5rem;">';
                    
                    errores.forEach(function(error) {
                        const errorTraducido = traducciones[error] || error;
                        mensajeHtml += '<li style="margin-bottom: 8px;">' + errorTraducido + '</li>';
                    });
                    
                    mensajeHtml += '</ul></div>';
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al restablecer contraseña',
                        html: mensajeHtml,
                        confirmButtonText: 'Entendido'
                    });
                }

                // Marcar campos con error
                @foreach($errors->keys() as $field)
                    const field_{{ $field }} = document.getElementById('{{ $field }}');
                    if (field_{{ $field }}) {
                        field_{{ $field }}.style.borderColor = '#dc2626';
                        field_{{ $field }}.style.borderWidth = '2px';
                    }
                @endforeach
            @endif
        });
    </script>
    @endpush
</x-layouts.auth>