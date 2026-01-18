/* archivo: includes/js/modales_globales.js */

/* ============================================================
   MODALES_GLOBALES.JS — Sistema universal de modales
   Compatible con PHP 5.6
   ============================================================ */

/* ------------------------------
   Modal genérico
--------------------------------*/
function mostrarModal(titulo, mensaje) {
    document.getElementById('modal_titulo').innerHTML = titulo;
    document.getElementById('modal_mensaje').innerHTML = mensaje;
    document.getElementById('modal_global').style.display = 'block';
}

function cerrarModal() {
    document.getElementById('modal_global').style.display = 'none';
}

/* ------------------------------
   Modal de éxito
--------------------------------*/
function modalExito(mensaje) {
    mostrarModal("Éxito", mensaje);
}

/* ------------------------------
   Modal de error
--------------------------------*/
function modalError(mensaje) {
    mostrarModal("Error", mensaje);
}

/* ------------------------------
   Modal de confirmación
--------------------------------*/
function modalConfirmar(mensaje, callback) {
    document.getElementById('modal_confirm_mensaje').innerHTML = mensaje;
    document.getElementById('modal_confirm').style.display = 'block';

    document.getElementById('btn_confirmar_si').onclick = function () {
        document.getElementById('modal_confirm').style.display = 'none';
        callback(true);
    };

    document.getElementById('btn_confirmar_no').onclick = function () {
        document.getElementById('modal_confirm').style.display = 'none';
        callback(false);
    };
}