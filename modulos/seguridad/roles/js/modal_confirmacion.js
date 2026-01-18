// archivo  :   /modulos/seguridad/roles/js/modal_confirmacion.js

// Manejo genérico de modal de confirmación

var callbackConfirmacion = null;

function confirmarAccion(titulo, mensaje, callback) {
    $("#modalConfirmacionTitulo").text(titulo || "Confirmar acción");
    $("#modalConfirmacionMensaje").text(mensaje || "¿Está seguro de continuar?");
    callbackConfirmacion = callback || null;

    var modal = new bootstrap.Modal(document.getElementById('modalConfirmacion'));
    modal.show();

    $("#btnModalConfirmar").off("click").on("click", function () {
        if (callbackConfirmacion) callbackConfirmacion();
        modal.hide();
    });
}