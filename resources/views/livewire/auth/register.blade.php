{{--
/**
 * Nombre de la vista           : register.blade.php
 * Descripción de la vista      : Vista para registro de nuevos usuarios, solicita información 
 *                                personal y credenciales
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
            :title="__('Crear cuenta')" 
            :description="__('Ingresa tus datos a continuación para crear tu cuenta')" 
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6" id="registerForm">
            @csrf
            
            <flux:input
                name="name"
                id="name"
                :label="__('Nombre')"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Juan Pérez')"
            />

            <flux:input
                name="email"
                id="email"
                :label="__('Correo electrónico')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@gmail.com"
            />

            <flux:input
                name="phone"
                id="phone"
                :label="__('Número de teléfono')"
                :value="old('phone')"
                type="tel"  
                autocomplete="tel"
                placeholder="5551234567"
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
                    data-test="register-user-button"
                    icon="user-plus"
                    icon-variant="outline"
                >
                    {{ __('Crear cuenta') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-black/60 dark:text-white/60">
            <span>{{ __('¿Ya tienes una cuenta?') }}</span>
            <flux:link 
                :href="route('login')" 
                wire:navigate 
                class="text-black hover:text-gray-700 dark:text-white dark:hover:text-gray-300"
            >
                {{ __('Iniciar sesión') }}
            </flux:link>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            
            // ============================================
            // VALIDACIÓN DEL FORMULARIO
            // ============================================
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Limpiar bordes de error previos
                    document.querySelectorAll('input').forEach(input => {
                        input.style.borderColor = '';
                    });
                    
                    const nombre = document.getElementById('name').value.trim();
                    const email = document.getElementById('email').value.trim();
                    const password = document.getElementById('password').value;
                    const passwordConfirmation = document.getElementById('password_confirmation').value;
                    
                    // Validar campos vacíos
                    if (!nombre || !email || !password || !passwordConfirmation) {
                        e.preventDefault();
                        showError('Por favor, completa todos los campos requeridos.', 'Campos incompletos');
                        return false;
                    }
                    
                    // Validar formato de email
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(email)) {
                        e.preventDefault();
                        const emailField = document.getElementById('email');
                        emailField.style.borderColor = '#dc2626';
                        emailField.style.borderWidth = '2px';
                        
                        showError('Por favor, ingresa un correo electrónico válido.', 'Correo inválido');
                        emailField.focus();
                        return false;
                    }
                    
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
                    'The email has already been taken.': 'El correo electrónico ya está registrado.',
                    'The password field must be at least 8 characters.': 'La contraseña debe tener al menos 8 caracteres.',
                    'The password field confirmation does not match.': 'La confirmación de contraseña no coincide.',
                    'The name field is required.': 'El campo nombre es obligatorio.',
                    'The email field is required.': 'El campo correo electrónico es obligatorio.',
                    'The phone field is required.': 'El campo teléfono es obligatorio.',
                    'The password field is required.': 'El campo contraseña es obligatorio.',
                    'The email field must be a valid email address.': 'El correo electrónico debe ser válido.'
                };
                
                // Un solo error
                if (errores.length === 1) {
                    const errorTraducido = traducciones[errores[0]] || errores[0];
                    showError(errorTraducido, 'Error en el registro');
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
                        title: 'Error en el registro',
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