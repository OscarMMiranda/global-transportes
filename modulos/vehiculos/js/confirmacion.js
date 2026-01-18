// archivo: /modulos/vehiculos/js/confirmacion.js
// Función para mostrar el modal de confirmación


function confirmarAccion(titulo, mensaje, callback) {
    $("#confirmTitulo").text(titulo || "Confirmar acción");
    $("#confirmMensaje").text(mensaje || "¿Está seguro de continuar?");
    $("#modalConfirmacion").modal("show");

    $("#btnConfirmarAccion").off("click").on("click", function () {
        $("#modalConfirmacion").modal("hide");
        if (typeof callback === "function") {
            callback();
        }
    });
}