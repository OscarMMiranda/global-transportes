// archivo: /modulos/mantenimiento/tipo_vehiculo/js/confirmacion.js
console.log("📦 confirmacion.js inicializado");

// ---------------------------------------------------------
// SISTEMA CENTRALIZADO DE CONFIRMACIÓN
// ---------------------------------------------------------

/**
 * Mostrar modal de confirmación y ejecutar callback si el usuario confirma.
 *
 * @param {string} titulo   - Título del modal
 * @param {string} mensaje  - Mensaje del modal
 * @param {function} accion - Función a ejecutar al confirmar
 */
function confirmarAccion(titulo, mensaje, accion) {

    // Establecer textos
    $("#tituloConfirmacion").text(titulo);
    $("#mensajeConfirmacion").text(mensaje);

    // Mostrar modal
    $("#modalConfirmacion").modal("show");

    // Evitar múltiples eventos acumulados
    $("#btnConfirmarAccion").off("click");

    // Ejecutar acción al confirmar
    $("#btnConfirmarAccion").on("click", function () {
        $("#modalConfirmacion").modal("hide");
        accion();
    });
}