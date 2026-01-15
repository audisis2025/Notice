/**
 * Nombre del archivo           : sweetalert-config.js
 * Descripción del archivo      : Archivo que configura SweetAlert2 y define
 *                                funciones helper para alertas
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 2.0
 * Fecha de mantenimiento       : 15/01/2026
 * Folio de mantenimiento       : 1
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Actualización a modales centrados con títulos en español
 * Responsable                  : Sistema
 * Revisor                      : Jesús Núñez
 */

import Swal from 'sweetalert2'; 

// ============================================
// CONFIGURACIÓN GLOBAL DE SWEETALERT2
// ============================================
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

// ============================================
// HELPERS DE ALERTAS
// ============================================

/**
 * Helper para mostrar mensaje de éxito centrado
 * @param {string} message - Mensaje a mostrar
 * @param {string} title - Título del modal (por defecto "¡Éxito!")
 */
window.showSuccess = function(message, title = '¡Éxito!') {
    Swal.fire({
        icon: 'success',
        title: title,
        text: message,
        confirmButtonText: 'Aceptar',
        confirmButtonColor: '#10b981',
        allowOutsideClick: true,
        allowEscapeKey: true
    });
};

/**
 * Helper para mostrar mensaje de error centrado
 * @param {string} message - Mensaje a mostrar
 * @param {string} title - Título del modal (por defecto "Oops...")
 */
window.showError = function(message, title = 'Oops...') {
    Swal.fire({
        icon: 'error',
        title: title,
        text: message,
        confirmButtonText: 'OK',
        confirmButtonColor: '#dc2626',
        allowOutsideClick: true,
        allowEscapeKey: true
    });
};

/**
 * Helper para mostrar mensaje de advertencia centrado
 * @param {string} message - Mensaje a mostrar
 * @param {string} title - Título del modal (por defecto "Advertencia")
 */
window.showWarning = function(message, title = 'Advertencia') {
    Swal.fire({
        icon: 'warning',
        title: title,
        text: message,
        confirmButtonText: 'Aceptar',
        confirmButtonColor: '#f59e0b',
        allowOutsideClick: true,
        allowEscapeKey: true
    });
};

/**
 * Helper para mostrar mensaje informativo centrado
 * @param {string} message - Mensaje a mostrar
 * @param {string} title - Título del modal (por defecto "Información")
 */
window.showInfo = function(message, title = 'Información') {
    Swal.fire({
        icon: 'info',
        title: title,
        text: message,
        confirmButtonText: 'Aceptar',
        confirmButtonColor: '#3b82f6',
        allowOutsideClick: true,
        allowEscapeKey: true
    });
};

/**
 * Helper para confirmación de eliminación
 * @param {function} callback - Función a ejecutar si se confirma
 * @param {string} title - Título del modal (por defecto "¿Estás seguro?")
 * @param {string} text - Texto descriptivo (por defecto "Esta acción no se puede revertir")
 */
window.confirmDelete = function(callback, title = '¿Estás seguro?', text = '¡No podrás revertir esto!') {
    Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        customClass: {
            confirmButton: 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition',
            cancelButton: 'px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition ml-2'
        },
        allowOutsideClick: true,
        allowEscapeKey: true
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
};

/**
 * Helper para confirmación genérica con promesa
 * @param {string} message - Mensaje a mostrar
 * @param {string} title - Título del modal (por defecto "¿Estás seguro?")
 * @param {object} options - Opciones adicionales
 * @returns {Promise} - Promesa que resuelve con el resultado
 */
window.showConfirm = function(message, title = '¿Estás seguro?', options = {}) {
    return Swal.fire({
        icon: 'warning',
        title: title,
        text: message,
        showCancelButton: true,
        confirmButtonText: options.confirmText || 'Sí, continuar',
        cancelButtonText: options.cancelText || 'Cancelar',
        confirmButtonColor: options.confirmColor || '#3b82f6',
        cancelButtonColor: options.cancelColor || '#6b7280',
        allowOutsideClick: options.allowOutsideClick !== false,
        allowEscapeKey: options.allowEscapeKey !== false
    });
};

/**
 * Helper para confirmación de eliminación con promesa
 * @param {string} message - Mensaje a mostrar (opcional)
 * @returns {Promise} - Promesa que resuelve con el resultado
 */
window.showDeleteConfirm = function(message = '¡No podrás revertir esto!') {
    return Swal.fire({
        icon: 'warning',
        title: '¿Estás seguro?',
        text: message,
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        customClass: {
            confirmButton: 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition',
            cancelButton: 'px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition ml-2'
        },
        allowOutsideClick: true,
        allowEscapeKey: true
    });
};

console.log('✅ SweetAlert2 configurado en español correctamente');
console.log('📋 Helpers disponibles: showSuccess, showError, showWarning, showInfo, confirmDelete, showConfirm, showDeleteConfirm');