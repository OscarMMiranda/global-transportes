// archivo: /modulos/asistencias/vacaciones/js/vacaciones_periodos.js
// ============================================================
// JS: DETALLE DE PERIODO VACACIONAL
// Archivo: vacaciones_periodos.js
// ============================================================

$(document).ready(function () {

    console.log("vacaciones_periodos.js cargado correctamente");

});


// ============================================================
// FUNCIÓN PRINCIPAL: CARGAR DETALLE DEL PERIODO
// ============================================================
function cargarDetallePeriodo(idPeriodo) {

    if (!idPeriodo) {
        console.error("ID de periodo no recibido");
        return;
    }

    $("#loaderDetallePeriodo").removeClass("d-none");

    $.ajax({
        url: "ajax/ajax_periodos.php",
        type: "POST",
        data: { id_periodo: idPeriodo },
        dataType: "json",

        success: function (response) {

            if (!response || !response.detalle) {
                alert("No se encontró información del periodo.");
                return;
            }

            var d = response.detalle;

            // Llenar inputs del modal
            $("#detallePeriodoInicio").val(d.inicio);
            $("#detallePeriodoFin").val(d.fin);
            $("#detallePeriodoEstado").val(d.estado);
            $("#detalleDiasGanados").val(d.dias_ganados);
            $("#detalleDiasUsados").val(d.dias_usados);
            $("#detalleDiasVendidos").val(d.dias_vendidos);
            $("#detalleDiasPendientes").val(d.dias_pendientes);

            // Cargar movimientos
            cargarMovimientosPeriodo(response.movimientos);
        },

        error: function (xhr, status, error) {
            console.error("Error AJAX:", error);
            alert("Error al cargar el detalle del periodo.");
        },

        complete: function () {
            $("#loaderDetallePeriodo").addClass("d-none");
        }
    });
}


// ============================================================
// FUNCIÓN: CARGAR MOVIMIENTOS DEL PERIODO
// ============================================================
function cargarMovimientosPeriodo(lista) {

    var tbody = $("#tablaMovimientosPeriodo tbody");
    tbody.empty();

    if (!lista || lista.length === 0) {
        tbody.append(
            '<tr><td colspan="6" class="text-center text-muted">No hay movimientos registrados</td></tr>'
        );
        return;
    }

    for (var i = 0; i < lista.length; i++) {

        var m = lista[i];

        tbody.append(
            '<tr>' +
                '<td>' + m.fecha + '</td>' +
                '<td>' + m.tipo + '</td>' +
                '<td>' + m.dias + '</td>' +
                '<td>' + m.descripcion + '</td>' +
                '<td>' + m.usuario + '</td>' +
                '<td>' + m.ip + '</td>' +
            '</tr>'
        );
    }
}
