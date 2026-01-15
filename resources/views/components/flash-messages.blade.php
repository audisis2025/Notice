{{--
/**
 * Nombre del componente       : flash-messages.blade.php
 * Descripción                 : Componente global para mostrar mensajes flash con SweetAlert2
 * Versión                     : 2.0
 * Fecha de creación           : 15/01/2026
 * Descripción del cambio      : Uso de Swal.fire() en centro en lugar de Toast
 * Responsable                 : Sistema
 */
--}}

@if (session('success') || session('error') || session('warning') || session('info'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session("success") }}',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#10b981',
                    allowOutsideClick: true,
                    allowEscapeKey: true
                });
            @endif
            
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ session("error") }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc2626',
                    allowOutsideClick: true,
                    allowEscapeKey: true
                });
            @endif
            
            @if (session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: '¿Estás seguro?',
                    text: '{{ session("warning") }}',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#f59e0b',
                    allowOutsideClick: true,
                    allowEscapeKey: true
                });
            @endif
            
            @if (session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: '{{ session("info") }}',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#3b82f6',
                    allowOutsideClick: true,
                    allowEscapeKey: true
                });
            @endif
        });
    </script>
@endif