/*
    archivo: /modulos/asistencias/js/historial_asistencia.js
    módulo: asistencias
    propósito: cargar historial de una asistencia en su modal
*/

console.log("historial_asistencia.js CARGADO");

$(document).on("click", ".btnHistorial", function () {

    let id = $(this).data("id");

    $.post('/modulos/asistencias/acciones/historial_asistencia.php', {
        id: id
    }, function (r) {

        if (!r.ok) {
            alert("Error al cargar historial: " + (r.error || "Error desconocido"));
            return;
        }

        let html = "";

        if (r.data.length === 0) {
            html = `
                <div class="alert alert-secondary">
                    No hay historial registrado para esta asistencia.
                </div>
            `;
        } else {

            html = `<ul class="list-group">`;

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
        }

        $("#historialContenido").html(html);
        $("#modalHistorial").modal("show");

    }, 'json')
    .fail(function (xhr) {
        console.log("ERROR AJAX HISTORIAL:", xhr.responseText);
        alert("Error de comunicación con el servidor.");
    });

});
