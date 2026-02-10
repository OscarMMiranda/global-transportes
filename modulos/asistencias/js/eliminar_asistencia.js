/*
    archivo: /modulos/asistencias/js/eliminar_asistencia.js
*/

console.log("eliminar_asistencia.js CARGADO");

$(document).on("click", ".btnEliminarAsistencia", function () {

    let id = $(this).data("id");

    if (!confirm("¿Está seguro de eliminar esta asistencia?")) {
        return;
    }

    $.post('/modulos/asistencias/acciones/eliminar_asistencia.php', { id }, function (r) {

        if (!r.ok) {
            alert("Error al eliminar: " + (r.error || "Error desconocido"));
            return;
        }

        if ($.fn.DataTable.isDataTable('#tablaAsistencias')) {
            $('#tablaAsistencias').DataTable().ajax.reload(null, false);
            return;
        }

        if (typeof cargarReporte === "function") {
            cargarReporte();
            return;
        }

        location.reload();

    }, 'json');
});
