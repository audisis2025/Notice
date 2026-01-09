/**
 * Nombre del archivo           : app.js
 * Descripción del archivo      : Archivo principal de JavaScript que importa
 *                                y configura las librerías del sistema
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

import './bootstrap';
import './sweetalert-config';
import Swal from 'sweetalert2';
import Alpine from 'alpinejs';

// Hacer disponibles globalmente
window.Swal = Swal;
window.Alpine = Alpine;

// Iniciar Alpine.js
Alpine.start(); 

// Listeners para eventos de Livewire
document.addEventListener('livewire:init', () => {
    // Éxito
    Livewire.on('success', (event) => {
        showSuccess(event.message);
    });

    // Error
    Livewire.on('error', (event) => {
        showError(event.message);
    });

    // Advertencia
    Livewire.on('warning', (event) => {
        showWarning(event.message);
    });

    // Info
    Livewire.on('info', (event) => {
        showInfo(event.message);
    });

    // Toast
    Livewire.on('toast', (event) => {
        showToast(event.message, event.type || 'success');
    });
});