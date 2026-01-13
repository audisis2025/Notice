{{--
/**
 * Nombre de la vista           : login.blade.php
 * Descripción de la vista      : Vista para inicio de sesión, solicita correo electrónico y 
 *                                contraseña para autenticación
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
        <x-auth-header :title="__('Ingresar a tu cuenta')" :description="__('Ingresa tu correo electrónico y contraseña a continuación para iniciar sesión')" />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6" id="loginForm">
            @csrf

            <flux:input
                name="email"
                id="email"
                :label="__('Correo electrónico')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@gmail.com"
            />

            <div class="relative">
                <flux:input
                    name="password"
                    id="password"
                    :label="__('Contraseña')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Contraseña')"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-sm end-0 text-black hover:text-gray-700 dark:text-white dark:hover:text-gray-300" :href="route('password.request')" wire:navigate>
                        {{ __('¿Olvidaste tu contraseña?') }}
                    </flux:link>
                @endif
            </div>

            <flux:checkbox name="remember" :label="__('Recordarme')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button 
                    variant="primary" 
                    type="submit" 
                    class="w-full bg-black text-white rounded-lg hover:bg-gray-900" 
                    data-test="login-button"
                    icon="user-circle"
                    icon-variant="outline"
                >
                    {{ __('Iniciar sesión') }}
                </flux:button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-black/60 dark:text-white/60">
                <span>{{ __('¿No tienes una cuenta?') }}</span>
                <flux:link :href="route('register')" wire:navigate class="text-black hover:text-gray-700 dark:text-white dark:hover:text-gray-300">
                    {{ __('Regístrate') }}
                </flux:link>
            </div>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    document.querySelectorAll('input').forEach(input => {
                        input.style.borderColor = '';
                    });

                    const email = document.getElementById('email').value.trim();
                    const password = document.getElementById('password').value;
                    
                    if (!email || !password) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Campos incompletos',
                            text: 'Por favor, completa todos los campos requeridos.',
                            confirmButtonText: 'Aceptar',
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
                            confirmButtonText: 'Aceptar',
                            confirmButtonColor: '#dc2626'
                        });
                        emailField.focus();
                        return false;
                    }
                });
            }
            
            @if(session('error_cuenta_bloqueada'))
                const emailField = document.getElementById('email');
                const passwordField = document.getElementById('password');
                if (emailField) emailField.value = '';
                if (passwordField) passwordField.value = '';
                
                Swal.fire({
                    icon: 'error',
                    title: 'Cuenta Bloqueada',
                    text: 'Tu cuenta ha sido bloqueada por infringir las normas de uso de la plataforma.',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#dc2626',
                    allowOutsideClick: false,
                    allowEscapeKey: true,
                    backdrop: true
                });
            @endif
            
            @if ($errors->any())
                const errores = @json($errors->all());
                
                @if(!session('error_cuenta_bloqueada'))
                    const traducciones = {
                        'These credentials do not match our records.': 'Las credenciales no coinciden con nuestros registros.',
                        'The email field is required.': 'El campo correo electrónico es obligatorio.',
                        'The password field is required.': 'El campo contraseña es obligatorio.',
                        'The email field must be a valid email address.': 'El correo electrónico debe ser válido.',
                        'Too many login attempts. Please try again in :seconds seconds.': 'Demasiados intentos de inicio de sesión. Intenta nuevamente en unos segundos.',
                        'Your account has been locked.': 'Tu cuenta ha sido bloqueada.',
                        'Your email address is not verified.': 'Tu correo electrónico no ha sido verificado.'
                    };
                    
                    if (errores.length === 1) {
                        const errorTraducido = traducciones[errores[0]] || errores[0];
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al iniciar sesión',
                            text: errorTraducido,
                            confirmButtonText: 'Aceptar',
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
                            title: 'Error al iniciar sesión',
                            html: mensajeHtml,
                            confirmButtonText: 'Aceptar',
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
            @endif
            
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Inicio de sesión exitoso!',
                    text: '{{ session("success") }}',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#10b981'
                });
            @endif
            
            @if (session('error') && !session('error_cuenta_bloqueada'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session("error") }}',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#dc2626'
                });
            @endif
            
            @if (session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: '{{ session("warning") }}',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#f59e0b'
                });
            @endif
            
            @if (session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: '{{ session("info") }}',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#3b82f6'
                });
            @endif

            @if (session('status'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session("status") }}',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#10b981'
                });
            @endif
        });
    </script>
    @endpush
</x-layouts.auth>