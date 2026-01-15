{{--
/**
 * Nombre de la vista           : confirm-password.blade.php
 * Descripción de la vista      : Vista para confirmar contraseña en áreas seguras
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

    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Confirm password')"
            :description="__('This is a secure area of the application. Please confirm your password before continuing.')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6" id="confirmPasswordForm">
            @csrf

            <flux:input
                name="password"
                id="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Password')"
                viewable
            />

            <flux:button 
                variant="primary" 
                type="submit" 
                class="w-full bg-black text-white rounded-lg hover:bg-gray-900" 
                data-test="confirm-password-button"
            >
                {{ __('Confirm') }}
            </flux:button>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('confirmPasswordForm');
            
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
                    
                    // Validar campo vacío
                    if (!password) {
                        e.preventDefault();
                        showError('Por favor, ingresa tu contraseña.', 'Campo requerido');
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
                    'The password field is required.': 'El campo contraseña es obligatorio.',
                    'The provided password was incorrect.': 'La contraseña proporcionada es incorrecta.',
                    'The password is incorrect.': 'La contraseña es incorrecta.'
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