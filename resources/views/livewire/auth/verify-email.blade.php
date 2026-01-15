{{--
/**
 * Nombre de la vista           : verify-email.blade.php
 * Descripción de la vista      : Vista para verificación de correo electrónico
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

    <div class="mt-4 flex flex-col gap-6">
        <flux:text class="text-center text-black dark:text-white">
            {{ __('Please verify your email address by clicking on the link we just emailed to you.') }}
        </flux:text>

        @if (session('status') == 'verification-link-sent')
            <flux:text class="text-center font-medium !dark:text-green-400 !text-green-600">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </flux:text>
        @endif

        <div class="flex flex-col items-center justify-between space-y-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <flux:button 
                    type="submit" 
                    variant="primary" 
                    class="w-full bg-black text-white rounded-lg hover:bg-gray-900"
                >
                    {{ __('Resend verification email') }}
                </flux:button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:button 
                    variant="ghost" 
                    type="submit" 
                    class="text-sm cursor-pointer text-black hover:text-gray-700 dark:text-white dark:hover:text-gray-300" 
                    data-test="logout-button"
                >
                    {{ __('Log out') }}
                </flux:button>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mostrar mensaje cuando se reenvía el enlace
            @if (session('status') == 'verification-link-sent')
                showSuccess(
                    'Se ha enviado un nuevo enlace de verificación a tu correo electrónico.',
                    '¡Enlace enviado!'
                );
            @endif
        });
    </script>
    @endpush
</x-layouts.auth>