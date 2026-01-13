{{--
/**
 * Nombre de la vista           : register.blade.php
 * Descripción de la vista      : Vista para registro de nuevos usuarios, solicita información 
 *                                personal y credenciales
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 1.0
 * Fecha de mantenimiento       : 
 * Folio de mantenimiento       : 
 * Tipo de mantenimiento        :
 * Descripción del mantenimiento: 
 * Responsable                  : 
 * Revisor                      : 
 */
--}}

<x-layouts.auth>
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
        <x-auth-header :title="__('Crear cuenta')" :description="__('Ingresa tus datos a continuación para crear tu cuenta')" />

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
            <flux:link :href="route('login')" wire:navigate class="text-black hover:text-gray-700 dark:text-white dark:hover:text-gray-300">
                {{ __('Iniciar sesión') }}
            </flux:link>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    document.querySelectorAll('input').forEach(input => {
                        input.style.borderColor = '';
                    });
                    
                    const nombre = document.getElementById('name').value.trim();
                    const email = document.getElementById('email').value.trim();
                    const password = document.getElementById('password').value;
                    const passwordConfirmation = document.getElementById('password_confirmation').value;
                    
                    if (!nombre || !email || !password || !passwordConfirmation) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Campos incompletos',
                            text: 'Por favor, completa todos los campos requeridos.',
                            confirmButtonText: 'Entendido',
                            confirmButtonColor: '#dc2626'
                        });
                        return false;
                    }
                    
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(email)) {
                        e.preventDefault();
                        const emailField = document.getElementById('email');
                        emailField.style.borderColor = '#dc2626';
                        emailField.style.borderWidth = '2px';
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Correo inválido',
                            text: 'Por favor, ingresa un correo electrónico válido.',
                            confirmButtonText: 'Entendido',
                            confirmButtonColor: '#dc2626'
                        });
                        emailField.focus();
                        return false;
                    }
                    
                    if (password.length < 8) {
                        e.preventDefault();
                        const passwordField = document.getElementById('password');
                        passwordField.style.borderColor = '#dc2626';
                        passwordField.style.borderWidth = '2px';
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Contraseña muy corta',
                            text: 'La contraseña debe tener al menos 8 caracteres.',
                            confirmButtonText: 'Entendido',
                            confirmButtonColor: '#dc2626'
                        });
                        passwordField.focus();
                        return false;
                    }
                    
                    if (password !== passwordConfirmation) {
                        e.preventDefault();
                        const passwordField = document.getElementById('password');
                        const confirmField = document.getElementById('password_confirmation');
                        
                        passwordField.style.borderColor = '#dc2626';
                        passwordField.style.borderWidth = '2px';
                        confirmField.style.borderColor = '#dc2626';
                        confirmField.style.borderWidth = '2px';
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Las contraseñas no coinciden',
                            text: 'Por favor, asegúrate de que ambas contraseñas sean iguales.',
                            confirmButtonText: 'Entendido',
                            confirmButtonColor: '#dc2626'
                        });
                        confirmField.focus();
                        return false;
                    }
                });
            }
            
            @if ($errors->any())
                const errores = @json($errors->all());
                
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
                
                if (errores.length === 1) {
                    const errorTraducido = traducciones[errores[0]] || errores[0];
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error en el registro',
                        text: errorTraducido,
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#dc2626'
                    });
                } else {
                    let mensajeHtml = '<div style="text-align: center;">';
                    mensajeHtml += '<ul style="margin: 0; padding-left: 0; list-style: none;">';
                    
                    errores.forEach(function(error) {
                        const errorTraducido = traducciones[error] || error;
                        mensajeHtml += '<li style="margin-bottom: 8px;">' + errorTraducido + '</li>';
                    });
                    
                    mensajeHtml += '</ul></div>';
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error en el registro',
                        html: mensajeHtml,
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#dc2626'
                    });
                }

                @foreach($errors->keys() as $field)
                    const field_{{ $field }} = document.getElementById('{{ $field }}');
                    if (field_{{ $field }}) {
                        field_{{ $field }}.style.borderColor = '#dc2626';
                        field_{{ $field }}.style.borderWidth = '2px';
                    }
                @endforeach
            @endif
            
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Registro exitoso!',
                    text: '{{ session("success") }}',
                    confirmButtonText: 'Continuar',
                    confirmButtonColor: '#10b981'
                });
            @endif
            
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session("error") }}',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#dc2626'
                });
            @endif
            
            @if (session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: '{{ session("warning") }}',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#f59e0b'
                });
            @endif
            
            @if (session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: '{{ session("info") }}',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#3b82f6'
                });
            @endif
        });
    </script>
    @endpush
</x-layouts.auth>