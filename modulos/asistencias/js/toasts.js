/*
    archivo: /modulos/asistencias/js/toasts.js
    módulo: asistencias
    propósito: sistema unificado de toasts Bootstrap
*/

console.log("toasts.js CARGADO");

/**
 * Muestra un toast por ID y opcionalmente reemplaza el mensaje.
 * @param {string} id - ID del toast (ej: 'toastSuccess')
 * @param {string} mensaje - Texto a mostrar en el cuerpo del toast
 */
function mostrarToast(id, mensaje) {

    var toastEl = document.getElementById(id);

    if (!toastEl) {
        console.warn("Toast no encontrado:", id);
        return;
    }

    // Reemplazar mensaje si se envía uno
    if (mensaje) {
        var body = toastEl.querySelector(".toast-body");
        if (body) {
            body.textContent = mensaje;
        }
    }

    var toast = new bootstrap.Toast(toastEl);
    toast.show();
}

/* Alias rápidos para cada tipo de toast */

function toastSuccess(mensaje) {
    mostrarToast("toastSuccess", mensaje || "Operación realizada correctamente.");
}

function toastError(mensaje) {
    mostrarToast("toastError", mensaje || "Ocurrió un error al procesar la solicitud.");
}

function toastWarning(mensaje) {
    mostrarToast("toastWarning", mensaje || "Revisa los datos ingresados.");
}

function toastInfo(mensaje) {
    mostrarToast("toastInfo", mensaje || "Información actualizada.");
}
