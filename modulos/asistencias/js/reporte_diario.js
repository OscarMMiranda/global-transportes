// ============================================================
//  archivo: /modulos/asistencias/js/reporte_diario.js
// ============================================================

console.log("reporte_diario.js CARGADO");

document.addEventListener("DOMContentLoaded", function () {

    // ============================================================
    // 1) REPORTE DIARIO (vista completa)
    // ============================================================

    if (document.getElementById("btnBuscarReporte")) {

        console.log("reporte_diario.js: MODO REPORTE DIARIO ACTIVO");

        function cargarReporte() {

            let fecha   = $("#filtro_fecha").val();
            let empresa = $("#filtro_empresa").val();

            $("#contenedorReporteDiario").html(`
                <div class="text-center p-4">
                    <div class="spinner-border"></div>
                    <p>Cargando reporte...</p>
                </div>
            `);

            // RUTA ABSOLUTA → INFALIBLE
            $.post('/modulos/asistencias/acciones/reporte_diario.php', {
                fecha: fecha,
                empresa_id: empresa
            }, function (r) {

                console.log("REPORTE RECIBIDO:", r);

                // Si backend devuelve error
                if (!r.ok) {
                    $("#contenedorReporteDiario").html(`
                        <div class="alert alert-danger">${r.error}</div>
                    `);
                    return;
                }

                // Si no hay datos
                if (!r.data || r.data.length === 0) {
                    $("#contenedorReporteDiario").html(`
                        <div class="alert alert-info">No hay asistencias para los filtros seleccionados.</div>
                    `);
                    return;
                }

                // Construcción de tabla
                let html = `
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Conductor</th>
                                    <th>Empresa</th>
                                    <th>Tipo</th>
                                    <th>Entrada</th>
                                    <th>Salida</th>
                                    <th>Obs.</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                r.data.forEach(item => {
                    html += `
                        <tr>
                            <td>${item.conductor}</td>
                            <td>${item.empresa}</td>
                            <td>${item.tipo}</td>
                            <td>${item.hora_entrada || '-'}</td>
                            <td>${item.hora_salida || '-'}</td>
                            <td>${item.observacion || ''}</td>
                            <td>
    <span style="white-space: nowrap;">

        <button class="btn btn-warning btn-sm me-1 btnEditarAsistencia"
                data-id="${item.id}">
            <i class="fa-solid fa-pen-to-square"></i>
        </button>

        <button class="btn btn-danger btn-sm me-1 btnEliminarAsistencia"
                data-id="${item.id}">
            <i class="fa-solid fa-trash"></i>
        </button>

        <button class="btn btn-info btn-sm btnHistorial"
                data-id="${item.id}">
            <i class="fa-solid fa-clock-rotate-left"></i>
        </button>

    </span>
</td>

                        </tr>
                    `;
                });

                html += `
                            </tbody>
                        </table>
                    </div>
                `;

                $("#contenedorReporteDiario").html(html);

            }, 'json')
            .fail(function (xhr) {

                console.log("ERROR AJAX REPORTE:", xhr.responseText);

                $("#contenedorReporteDiario").html(`
                    <div class="alert alert-danger">Error de comunicación con el servidor</div>
                `);
            });
        }

        // Listener del botón BUSCAR
        $("#btnBuscarReporte").on("click", cargarReporte);

        // Cargar reporte inicial automáticamente
        cargarReporte();

    } else {

        console.log("reporte_diario.js: sin btnBuscarReporte, modo reporte diario NO aplica aquí");
    }

    // ============================================================
    // 2) HISTORIAL DEL DÍA (modal registrar asistencia)
    // ============================================================

    $(document).on('change', '#fecha', function () {

        let conductor_id = $('#conductor_id').val();
        let fecha        = $('#fecha').val();

        if (conductor_id === "" || fecha === "") {
            $('#historialDia').html('');
            return;
        }

        $.post('/modulos/asistencias/acciones/cargar_historial_dia.php', {
            conductor_id: conductor_id,
            fecha: fecha
        }, function (r) {

            if (r.ok && r.data) {

                let h = r.data;

                let html = `
                    <div class="alert alert-info">
                        <strong>Registro existente:</strong><br>
                        Tipo: ${h.tipo}<br>
                        Entrada: ${h.hora_entrada || '-'}<br>
                        Salida: ${h.hora_salida || '-'}<br>
                        Observación: ${h.observacion || '-'}
                    </div>
                `;

                $('#historialDia').html(html);

            } else {
                $('#historialDia').html(`
                    <div class="alert alert-secondary">
                        No hay asistencia registrada para este día.
                    </div>
                `);
            }

        }, 'json');

    });

});

// EDITAR
$(document).on("click", ".btnEditarAsistencia", function () {

    let id = $(this).data("id");
    console.log("Editar asistencia:", id);

    $.post('/modulos/asistencias/acciones/obtener_asistencia.php', { id }, function (r) {

        if (!r.ok) {
            alert(r.error);
            return;
        }

        let d = r.data;

        // cargar tipos
        $.post('/modulos/asistencias/acciones/listar_tipos.php', {}, function (t) {

            if (t.ok) {
                let html = "";
                t.data.forEach(tp => {
                    html += `<option value="${tp.id}" ${tp.id == d.tipo_id ? 'selected' : ''}>${tp.descripcion}</option>`;
                });
                $("#edit_tipo").html(html);
            }

            $("#edit_id").val(id);
            $("#edit_entrada").val(d.hora_entrada);
            $("#edit_salida").val(d.hora_salida);
            $("#edit_obs").val(d.observacion);

			// ← AQUÍ
			$("#edit_titulo_contexto").text(
			`Editar asistencia — ${d.conductor} — ${d.fecha}`
			);


            // ABRIR MODAL (Bootstrap 4)
            $('#modalEditarAsistencia').modal('show');

        }, 'json');

    }, 'json');
});


$(document).on("click", ".btnEliminarAsistencia", function () {

    let id = $(this).data("id");

    if (!confirm("¿Eliminar esta asistencia? Esta acción no se puede deshacer.")) {
        return;
    }

    $.post('/modulos/asistencias/acciones/eliminar_asistencia.php', {
        id: id
    }, function (r) {

        if (r.ok) {
            alert("Asistencia eliminada correctamente");

            if (typeof cargarReporte === "function") {
                cargarReporte();
            } else {
                location.reload();
            }

        } else {
            alert("Error al eliminar: " + (r.error || "Error desconocido"));
        }

    }, 'json')
    .fail(function (xhr) {
        console.log("ERROR AJAX ELIMINAR:", xhr.responseText);
        alert("Error de comunicación con el servidor.");
    });

});


// HISTORIAL
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

            html = `
                <ul class="list-group">
            `;

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


// GUARDAR EDICIÓN
$(document).on("click", "#btnGuardarEdicion", function () {

    let id      = $("#edit_id").val();
    let tipo    = $("#edit_tipo").val();
    let entrada = $("#edit_entrada").val();
    let salida  = $("#edit_salida").val();
    let obs     = $("#edit_obs").val();

    console.log("GUARDAR EDICIÓN:", { id, tipo, entrada, salida, obs });

    $.post('/modulos/asistencias/acciones/editar_asistencia.php', {
        id: id,
        tipo: tipo,
        entrada: entrada,
        salida: salida,
        obs: obs
    }, function (r) {

        console.log("RESPUESTA EDITAR:", r);

        if (r.ok) {
            alert("Asistencia actualizada correctamente");
            // recargar tabla
            if (typeof cargarReporte === "function") {
                cargarReporte();
            } else {
                // fallback: recargar página
                location.reload();
            }
            $('#modalEditarAsistencia').modal('hide');
        } else {
            alert("Error al guardar: " + (r.error || 'Error desconocido'));
        }

    }, 'json').fail(function (xhr) {
        console.log("ERROR AJAX EDITAR:", xhr.responseText);
        alert("Error de comunicación con el servidor al guardar.");
    });
});

