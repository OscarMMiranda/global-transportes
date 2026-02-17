/*
    archivo: /modulos/asistencias/js/historial_asistencia.js
    propósito: cargar historial de una asistencia y mostrar modal
*/

console.log("historial_asistencia.js CARGADO");

// ======================================================
// EVENTO PRINCIPAL
// ======================================================
$(document).on("click", ".btnHistorialAsistencia", function () {

    let id = $(this).data("id");
    console.log("CLICK EN HISTORIAL, ID:", id);

    // Mostrar spinner inicial
    $("#contenedorHistorial").html(`
        <div class="text-center p-4">
            <div class="spinner-border"></div>
            <p>Cargando historial...</p>
        </div>
    `);

    // Abrir modal (Bootstrap 4)
    $("#modalHistorialAsistencia").modal("show");

    // Llamar al backend
    $.post('/modulos/asistencias/acciones/obtener_historial.php', { id }, function (r) {

        console.log("RESPUESTA HISTORIAL:", r);

        if (!r.ok) {
            $("#contenedorHistorial").html(`
                <div class="alert alert-danger">${r.error}</div>
            `);
            return;
        }

        if (r.data.length === 0) {
            $("#contenedorHistorial").html(`
                <div class="alert alert-info">
                    No hay historial registrado para esta asistencia.
                </div>
            `);
            return;
        }

        let html = `<ul class="list-group">`;

        r.data.forEach(h => {
            html += `
                <li class="list-group-item">
                    <strong>${h.fecha_hora}</strong><br>
                    Acción: ${h.accion}<br>
                    Usuario: ${h.usuario}<br>
                    Detalle: ${h.detalle}
                </li>
            `;
        });

        html += `</ul>`;

        $("#contenedorHistorial").html(html);

    }, 'json')
    .fail(function (xhr) {
        console.log("ERROR AJAX HISTORIAL:", xhr.responseText);
        $("#contenedorHistorial").html(`
            <div class="alert alert-danger">
                Error de comunicación con el servidor.
            </div>
        `);
    });

});
