// archivo: /modulos/seguridad/usuarios/js/modal_confirmacion.js

var callbackConfirmacion = null;

function confirmarAccion(mensaje, callback) {
    $("#modalConfirmacionMensaje").text(mensaje);
    callbackConfirmacion = callback;

    var modal = new bootstrap.Modal(document.getElementById("modalConfirmacion"));
    modal.show();
}

$("#btnConfirmarAccion").on("click", function () {
    if (typeof callbackConfirmacion === "function") {
        callbackConfirmacion();
    }
    callbackConfirmacion = null;

    var modal = bootstrap.Modal.getInstance(document.getElementById("modalConfirmacion"));
    modal.hide();
});