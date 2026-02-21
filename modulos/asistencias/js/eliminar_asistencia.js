/*
    archivo: /modulos/asistencias/js/eliminar_asistencia.js
    módulo: asistencias
    propósito: eliminar asistencia usando modal corporativo
*/

console.log("eliminar_asistencia.js CARGADO");

// ============================================================
// ABRIR MODAL
// ============================================================
$(document).on("click", ".btnEliminarAsistencia", function () {

    let id = $(this).data("id");
    console.log("CLICK EN ELIMINAR, ID:", id);

    if (!id) {
        toastError("ID inválido.");
        return;
    }

    $("#asistencia_id_eliminar").val(id);
    $("#modalEliminarAsistencia").modal("show");
});

// ============================================================
// CONFIRMAR ELIMINACIÓN
// ============================================================
$(document).on("click", "#btnConfirmarEliminarAsistencia", function () {

    let id = $("#asistencia_id_eliminar").val();

    $.post('/modulos/asistencias/acciones/eliminar_asistencia.php', 
        { id: id }, 
        function (r) {

            console.log("RESPUESTA DEL SERVIDOR:", r);

            if (!r || !r.ok) {
                toastError(r && r.error ? r.error : "No se pudo eliminar la asistencia.");
                return;
            }

            toastSuccess("Asistencia eliminada correctamente.");

            $("#modalEliminarAsistencia").modal("hide");

            // Si estás en REPORTE DIARIO
            if (typeof RD !== "undefined" && typeof RD.cargarReporte === "function") {
                RD.cargarReporte();
                return;
            }

            // Fallback
            location.reload();

        }, 'json'
    ).fail(function (xhr) {
        console.log("ERROR AJAX:", xhr.responseText);
        toastError("Error de comunicación con el servidor.");
    });

});
