// Configuración global de SweetAlert2
window.Swal = Swal.mixin({
    customClass: {
        confirmButton: 'px-4 py-2 bg-black text-white rounded-lg hover:bg-[#494949] transition',
        cancelButton: 'px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition ml-2',
        denyButton: 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition ml-2'
    },
    buttonsStyling: false,
    confirmButtonText: 'Aceptar',
    cancelButtonText: 'Cancelar',
    denyButtonText: 'No',
});

// Helper para confirmación de eliminación
window.confirmDelete = function(callback, title = '¿Estás seguro?', text = 'Esta acción no se puede revertir') {
    Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        customClass: {
            confirmButton: 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition',
            cancelButton: 'px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition ml-2'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
};

// Helper para éxito
window.showSuccess = function(message, title = '¡Éxito!') {
    Swal.fire({
        icon: 'success',
        title: title,
        text: message,
        timer: 3000,
        showConfirmButton: false
    });
};

// Helper para error
window.showError = function(message, title = 'Error') {
    Swal.fire({
        icon: 'error',
        title: title,
        text: message,
        confirmButtonText: 'Entendido'
    });
};

// Helper para advertencia
window.showWarning = function(message, title = 'Advertencia') {
    Swal.fire({
        icon: 'warning',
        title: title,
        text: message,
        confirmButtonText: 'Entendido'
    });
};

// Helper para información
window.showInfo = function(message, title = 'Información') {
    Swal.fire({
        icon: 'info',
        title: title,
        text: message,
        confirmButtonText: 'Entendido'
    });
};

// Toast para notificaciones rápidas
window.showToast = function(message, icon = 'success') {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: icon,
        title: message
    });
};
