{{--
/**
 * Nombre de la vista           : forgot-password.blade.php
 * Descripción de la vista      : Vista para solicitar enlace de restablecimiento de contraseña
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 2.1
 * Fecha de mantenimiento       : 15/01/2026
 * Folio de mantenimiento       : 
 * Tipo de mantenimiento        : Correctivo
 * Descripción del mantenimiento: Traducción completa al español
 * Responsable                  : Sistema
 * Revisor                      : 
 */
--}}

<x-layouts.auth>
    {{-- Componente de mensajes flash --}}
    <x-flash-messages />

    <div class="flex flex-col gap-6">
        <x-auth-header 
            :title="__('¿Olvidaste tu contraseña?')" 
            :description="__('Ingresa tu correo electrónico para recibir un enlace de restablecimiento')" 
        />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6" id="forgotPasswordForm">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                id="email"
                :label="__('Correo electrónico')"
                :value="old('email')"
                type="email"
                required
                autofocus
                placeholder="email@gmail.com"
            />

            <flux:button 
                variant="primary" 
                type="submit" 
                class="w-full bg-black text-white rounded-lg hover:bg-gray-900" 
                data-test="email-password-reset-link-button"
                icon="envelope"
                icon-variant="outline"
            >
                {{ __('Enviar enlace de restablecimiento') }}
            </flux:button>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-black/60 dark:text-white/60">
            <span>{{ __('O puedes') }}</span>
            <flux:link 
                :href="route('login')" 
                wire:navigate
                class="text-black hover:text-gray-700 dark:text-white dark:hover:text-gray-300"
            >
                {{ __('volver a iniciar sesión') }}
            </flux:link>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('forgotPasswordForm');
            
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
                    
                    // Validar campo vacío
                    if (!email) {
                        e.preventDefault();
                        showError('Por favor, ingresa tu correo electrónico.', 'Campo requerido');
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
            // ERRORES DE VALIDACIÓN
            // ============================================
            @if ($errors->any())
                const errores = @json($errors->all());
                
                // Traducciones de errores comunes
                const traducciones = {
                    'The email field is required.': 'El campo correo electrónico es obligatorio.',
                    'The email field must be a valid email address.': 'El correo electrónico debe ser válido.',
                    'We can\'t find a user with that email address.': 'No encontramos un usuario con ese correo electrónico.',
                    'passwords.user': 'No encontramos un usuario con ese correo electrónico.',
                    'passwords.throttled': 'Por favor espera antes de intentar nuevamente.'
                };
                
                // Un solo error
                if (errores.length === 1) {
                    const errorTraducido = traducciones[errores[0]] || errores[0];
                    showError(errorTraducido, 'Error');
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
                        title: 'Error',
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