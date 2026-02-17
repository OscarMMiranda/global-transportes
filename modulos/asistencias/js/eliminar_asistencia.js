/*
    archivo: /modulos/asistencias/js/eliminar_asistencia.js
    módulo: asistencias
    propósito: eliminar una asistencia desde el submódulo REPORTE DIARIO
*/

console.log("eliminar_asistencia.js CARGADO");

$(document).on("click", ".btnEliminarAsistencia", function () {

    let id = $(this).data("id");
    console.log("CLICK EN ELIMINAR, ID:", id);

    if (!id) {
        toastError("ID inválido.");
        return;
    }

    if (!confirm("¿Está seguro de eliminar esta asistencia?")) {
        return;
    }

    $.post('/modulos/asistencias/acciones/eliminar_asistencia.php', { id }, function (r) {

        console.log("RESPUESTA DEL SERVIDOR:", r);

        if (!r || !r.ok) {
            toastError(r && r.error ? r.error : "No se pudo eliminar la asistencia.");
            return;
        }

        toastSuccess("Asistencia eliminada correctamente.");

        // REPORTE DIARIO (tabla.js)
        if (typeof RD !== "undefined" && typeof RD.cargarReporte === "function") {
            console.log("Recargando reporte diario…");
            RD.cargarReporte();
            return;
        }

        // Fallback
        console.log("Recargando página (fallback)...");
        location.reload();

    }, 'json')
    .fail(function (xhr) {
        console.log("ERROR AJAX:", xhr.responseText);
        toastError("Error de comunicación con el servidor.");
    });

});
