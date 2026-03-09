// archivo: /modulos/asistencias/vacaciones/js/vacaciones_calendario.js
// ============================================================
// JS: CALENDARIO DE VACACIONES
// Archivo: vacaciones_calendario.js
// ============================================================

$(document).ready(function () {

    console.log("vacaciones_calendario.js cargado correctamente");

    // Cargar calendario al iniciar la vista
    if ($("#contenedorCalendario").length > 0) {
        cargarCalendario();
    }

    // Cambiar de mes
    $("#btnMesAnterior").on("click", function () {
        cambiarMes(-1);
    });

    $("#btnMesSiguiente").on("click", function () {
        cambiarMes(1);
    });

});


// ============================================================
// VARIABLES GLOBALES
// ============================================================
var calendarioMes = null;
var calendarioAnio = null;


// ============================================================
// FUNCIÓN PRINCIPAL: CARGAR CALENDARIO
// ============================================================
function cargarCalendario() {

    // Si no hay mes cargado, usar el actual
    var fecha = new Date();

    if (calendarioMes === null) {
        calendarioMes = fecha.getMonth() + 1; // 1-12
    }

    if (calendarioAnio === null) {
        calendarioAnio = fecha.getFullYear();
    }

    $("#loaderCalendario").removeClass("d-none");

    $.ajax({
        url: "ajax/ajax_calendario.php",
        type: "POST",
        data: {
            mes: calendarioMes,
            anio: calendarioAnio
        },
        dataType: "json",

        success: function (response) {

            if (!response || !response.dias) {
                $("#contenedorCalendario").html(
                    '<div class="text-muted text-center">No se pudo cargar el calendario</div>'
                );
                return;
            }

            renderizarCalendario(response);
        },

        error: function (xhr, status, error) {
            console.error("Error AJAX:", error);
            alert("Error al cargar el calendario.");
        },

        complete: function () {
            $("#loaderCalendario").addClass("d-none");
        }
    });
}


// ============================================================
// FUNCIÓN: CAMBIAR MES
// ============================================================
function cambiarMes(delta) {

    calendarioMes += delta;

    if (calendarioMes < 1) {
        calendarioMes = 12;
        calendarioAnio--;
    }

    if (calendarioMes > 12) {
        calendarioMes = 1;
        calendarioAnio++;
    }

    cargarCalendario();
}


// ============================================================
// FUNCIÓN: RENDERIZAR CALENDARIO
// ============================================================
function renderizarCalendario(data) {

    var dias = data.dias;
    var html = '';

    html += '<table class="table table-bordered table-sm calendario-table">';
    html += '<thead class="table-light">';
    html += '<tr>';
    html += '<th>Lun</th>';
    html += '<th>Mar</th>';
    html += '<th>Mié</th>';
    html += '<th>Jue</th>';
    html += '<th>Vie</th>';
    html += '<th class="text-primary">Sáb</th>';
    html += '<th class="text-danger">Dom</th>';
    html += '</tr>';
    html += '</thead>';
    html += '<tbody>';

    var fila = '<tr>';
    var contador = 0;

    for (var i = 0; i < dias.length; i++) {

        var d = dias[i];

        // Si es el primer día del mes, agregar espacios
        if (i === 0) {
            for (var k = 1; k < d.dia_semana; k++) {
                fila += '<td></td>';
                contador++;
            }
        }

        var clase = '';

        if (d.tipo === 'usado') clase = 'bg-danger text-white';
        if (d.tipo === 'pendiente') clase = 'bg-warning';
        if (d.tipo === 'disponible') clase = 'bg-success text-white';
        if (d.tipo === 'feriado') clase = 'bg-info text-white';

        fila += '<td class="' + clase + '">' + d.dia + '</td>';
        contador++;

        if (contador === 7) {
            html += fila + '</tr>';
            fila = '<tr>';
            contador = 0;
        }
    }

    if (contador > 0) {
        for (var j = contador; j < 7; j++) {
            fila += '<td></td>';
        }
        html += fila + '</tr>';
    }

    html += '</tbody>';
    html += '</table>';

    $("#contenedorCalendario").html(html);
}
