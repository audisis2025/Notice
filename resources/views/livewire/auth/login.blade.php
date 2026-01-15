{{--
/**
 * Nombre de la vista           : login.blade.php
 * Descripción de la vista      : Vista para inicio de sesión, solicita correo electrónico y 
 *                                contraseña para autenticación
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 3.0
 * Fecha de mantenimiento       : 15/01/2026
 * Folio de mantenimiento       : 
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Uso exclusivo de helpers globales de SweetAlert2
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
            :title="__('Ingresar a tu cuenta')" 
            :description="__('Ingresa tu correo electrónico y contraseña a continuación para iniciar sesión')" 
        />

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
                    <flux:link 
                        class="absolute top-0 text-sm end-0 text-black hover:text-gray-700 dark:text-white dark:hover:text-gray-300" 
                        :href="route('password.request')" 
                        wire:navigate
                    >
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
                <flux:link 
                    :href="route('register')" 
                    wire:navigate 
                    class="text-black hover:text-gray-700 dark:text-white dark:hover:text-gray-300"
                >
                    {{ __('Regístrate') }}
                </flux:link>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            
            // ============================================
            // VALIDACIÓN DEL FORMULARIO
            // ============================================
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Limpiar bordes de error previos
                    document.querySelectorAll('input').forEach(input => {
                        input.style.borderColor = '';
                    });

                    const email = document.getElementById('email').value.trim();
                    const password = document.getElementById('password').value;
                    
                    // Validar campos vacíos
                    if (!email || !password) {
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
                });
            }
            
            // ============================================
            // CUENTA BLOQUEADA
            // ============================================
            @if(session('error_cuenta_bloqueada'))
                // Limpiar campos
                const emailField = document.getElementById('email');
                const passwordField = document.getElementById('password');
                if (emailField) emailField.value = '';
                if (passwordField) passwordField.value = '';
                
                // ✅ USAR HELPER showError
                showError('Tu cuenta ha sido bloqueada por infringir las normas de uso de la plataforma.', 'Cuenta Bloqueada');
            @endif
            
            // ============================================
            // ERRORES DE VALIDACIÓN DE LARAVEL
            // ============================================
            @if ($errors->any())
                @if(!session('error_cuenta_bloqueada'))
                    const errores = @json($errors->all());
                    
                    // Ya NO necesitamos traducciones porque Laravel YA envía en español
                    // Los mensajes vienen directamente traducidos desde lang/es/
                    
                    // Si por alguna razón llega un mensaje en inglés, lo traducimos:
                    const traduccionesRespaldo = {
                        'These credentials do not match our records.': 'Las credenciales no coinciden con nuestros registros.',
                        'The email field is required.': 'El campo correo electrónico es obligatorio.',
                        'The password field is required.': 'El campo contraseña es obligatorio.',
                        'The email field must be a valid email address.': 'El correo electrónico debe ser válido.',
                        'Too many login attempts. Please try again in :seconds seconds.': 'Demasiados intentos de inicio de sesión. Intenta nuevamente en unos segundos.',
                        'Your account has been locked.': 'Tu cuenta ha sido bloqueada.',
                        'Your email address is not verified.': 'Tu correo electrónico no ha sido verificado.'
                    };
                    
                    // Usar el mensaje tal cual viene, o traducirlo si está en inglés
                    const traducirMensaje = (msg) => traduccionesRespaldo[msg] || msg;
                    
                    // Un solo error - ✅ USAR HELPER showError
                    if (errores.length === 1) {
                        const errorTraducido = traducirMensaje(errores[0]);
                        showError(errorTraducido, 'Error al iniciar sesión');
                    } 
                    // Múltiples errores
                    else {
                        let mensajeHtml = '<div style="text-align: left; padding-left: 1rem;">';
                        mensajeHtml += '<ul style="margin: 0; padding-left: 1.5rem;">';
                        
                        errores.forEach(function(error) {
                            const errorTraducido = traducirMensaje(error);
                            mensajeHtml += '<li style="margin-bottom: 8px;">' + errorTraducido + '</li>';
                        });
                        
                        mensajeHtml += '</ul></div>';
                        
                        // Múltiples errores requieren Swal.fire con HTML
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al iniciar sesión',
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
            @endif
        });
    </script>
    @endpush
</x-layouts.auth>