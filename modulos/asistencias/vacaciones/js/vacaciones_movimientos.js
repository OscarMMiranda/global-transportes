// archivo: /modulos/asistencias/vacaciones/js/vacaciones_movimientos.js
// ============================================================
// JS: MOVIMIENTOS DE VACACIONES
// Archivo: vacaciones_movimientos.js
// ============================================================

$(document).ready(function () {

    console.log("vacaciones_movimientos.js cargado correctamente");

    // Cargar movimientos al abrir el modal
    $("#modalMovimientos").on("shown.bs.modal", function () {
        cargarMovimientos();
    });

    // Botón filtrar
    $("#btnFiltrarMovimientos").on("click", function () {
        cargarMovimientos();
    });

});


// ============================================================
// FUNCIÓN PRINCIPAL: CARGAR MOVIMIENTOS
// ============================================================
function cargarMovimientos() {

    var conductor = $("#movConductor").val();
    var tipo      = $("#movTipo").val();
    var anio      = $("#movAnio").val();

    $("#loaderMovimientos").removeClass("d-none");

    $.ajax({
        url: "../ajax/ajax_movimientos.php",
        type: "POST",
        data: {
            conductor: conductor,
            tipo: tipo,
            anio: anio
        },
        dataType: "json",

        success: function (response) {

            var tbody = $("#tablaMovimientosVacaciones tbody");
            tbody.empty();

            if (!response || response.length === 0) {
                tbody.append(
                    '<tr><td colspan="7" class="text-center text-muted">No se encontraron movimientos</td></tr>'
                );
                return;
            }

            for (var i = 0; i < response.length; i++) {

                var item = response[i];

                tbody.append(
                    '<tr>' +
                        '<td>' + item.fecha + '</td>' +
                        '<td>' + item.tipo + '</td>' +
                        '<td>' + item.dias + '</td>' +
                        '<td>' + item.periodo + '</td>' +
                        '<td>' + item.descripcion + '</td>' +
                        '<td>' + item.usuario + '</td>' +
                        '<td>' + item.ip + '</td>' +
                    '</tr>'
                );
            }
        },

        error: function (xhr, status, error) {
            console.error("Error AJAX:", error);
            alert("Error al cargar los movimientos.");
        },

        complete: function () {
            $("#loaderMovimientos").addClass("d-none");
        }
    });
}
