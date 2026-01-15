import './bootstrap';
import './sweetalert-config'; // 👈 aquí se define window.Swal correctamente
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// ============================================
// LISTENERS PARA EVENTOS DE LIVEWIRE
// ============================================
document.addEventListener('livewire:init', () => {

    Livewire.on('success', (event) => {
        showSuccess(event.message);
    });

    Livewire.on('error', (event) => {
        showError(event.message);
    });

    Livewire.on('warning', (event) => {
        showWarning(event.message);
    });

    Livewire.on('info', (event) => {
        showInfo(event.message);
    });

    Livewire.on('toast', (event) => {
        showToast(event.message, event.type || 'success');
    });
});

// ============================================
// FLASH MESSAGES BLADE
// ============================================
document.addEventListener('DOMContentLoaded', function() {

    const successMessage = document.querySelector('[data-flash-success]');
    const errorMessage = document.querySelector('[data-flash-error]');
    const warningMessage = document.querySelector('[data-flash-warning]');
    const infoMessage = document.querySelector('[data-flash-info]');

    if (successMessage) showToast(successMessage.dataset.flashSuccess, 'success');
    if (errorMessage) showError(errorMessage.dataset.flashError);
    if (warningMessage) showWarning(warningMessage.dataset.flashWarning);
    if (infoMessage) showInfo(infoMessage.dataset.flashInfo);
});

console.log('✅ SweetAlert2 configurado en español correctamente');
