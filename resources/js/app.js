import './bootstrap';
import './sweetalert-config';
import Swal from 'sweetalert2';
window.Swal = Swal;

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