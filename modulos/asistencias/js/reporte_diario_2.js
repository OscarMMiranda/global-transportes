// ============================================================
//  archivo: /modulos/asistencias/js/reporte_diario.js

console.log("reporte_diario.js CARGADO");

document.addEventListener("DOMContentLoaded", function () {


    // 1) REPORTE DIARIO (vista completa)
    if (document.getElementById("btnBuscarReporte")) {

        console.log("reporte_diario.js: MODO REPORTE DIARIO ACTIVO");

        window.cargarReporte = function () {

            let fecha   = $("#filtro_fecha").val();
            let empresa = $("#filtro_empresa").val();

            $("#contenedorReporteDiario").html(`
                <div class="text-center p-4">
                    <div class="spinner-border"></div>
                    <p>Cargando reporte...</p>
                </div>
            `);

            $.post('/modulos/asistencias/acciones/reporte_diario.php', {
                fecha: fecha,
                empresa_id: empresa
            }, function (r) {

                console.log("REPORTE RECIBIDO:", r);

                if (!r.ok) {
                    $("#contenedorReporteDiario").html(`
                        <div class="alert alert-danger">${r.error}</div>
                    `);
                    return;
                }

                if (!r.data || r.data.length === 0) {
                    $("#contenedorReporteDiario").html(`
                        <div class="alert alert-info">No hay asistencias para los filtros seleccionados.</div>
                    `);
                    return;
                }

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

        $("#btnBuscarReporte").on("click", cargarReporte);
        cargarReporte();

    } else {
        console.log("reporte_diario.js: sin btnBuscarReporte, modo reporte diario NO aplica aquí");
    }

    // 2) HISTORIAL DEL DÍA
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


// 3) CARGAR ASISTENCIA EN MODAL DE EDICIÓN
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
                    // ← CORREGIDO: value = codigo, no id
                    html += `<option value="${tp.codigo}" ${tp.codigo == d.codigo_tipo ? 'selected' : ''}>${tp.descripcion}</option>`;
                });
                $("#edit_tipo").html(html);
            }
            $("#edit_id").val(id);
            $("#edit_entrada").val(d.hora_entrada);
            $("#edit_salida").val(d.hora_salida);
            $("#edit_obs").val(d.observacion);
            $("#edit_titulo_contexto").text(
                `Editar asistencia — ${d.conductor} — ${d.fecha}`
            );
            $('#modalEditarAsistencia').modal('show');
        }, 'json');

    }, 'json');
});


// 4) ELIMINAR ASISTENCIA
$(document).on("click", ".btnEliminarAsistencia", function () {

    let id = $(this).data("id");

    if (!confirm("¿Seguro que desea eliminar esta asistencia?")) {
        return;
    }

    $.post('/modulos/asistencias/acciones/eliminar_asistencia.php', { id }, function (r) {

        if (!r.ok) {
            toastError(r.error || "No se pudo eliminar la asistencia.");
            return;
        }

        toastSuccess("Asistencia eliminada correctamente.");

        if (typeof cargarReporte === "function") {
            cargarReporte();
        }

    }, 'json')
    .fail(function (xhr) {
        toastError("Error de comunicación con el servidor.");
        console.log("ERROR AJAX:", xhr.responseText);
    });

});

// ============================================================
// 5) HISTORIAL DE ASISTENCIA
// ============================================================

$(document).on("click", ".btnHistorial", function () {

    let id = $(this).data("id");
    console.log("Historial asistencia:", id);

    // Mostrar modal inmediatamente
    $('#modalHistorialAsistencia').modal('show');

    // Loader inicial
    $("#contenedorHistorial").html(`
        <div class="text-center p-4">
            <div class="spinner-border"></div>
            <p>Cargando historial...</p>
        </div>
    `);

    // Llamada AJAX (formato 100% compatible con PHP 5.6 + IIS)
    $.ajax({
        url: '/modulos/asistencias/acciones/obtener_historial.php',
        type: 'POST',
        data: { id: id },   // ← ESTA ES LA CLAVE
        dataType: 'json',

        success: function (r) {
            console.log("RESPUESTA HISTORIAL:", r);

            if (!r.ok) {
                $("#contenedorHistorial").html(`
                    <div class="alert alert-danger">${r.error}</div>
                `);
                return;
            }

            if (!r.data || r.data.length === 0) {
                $("#contenedorHistorial").html(`
                    <div class="alert alert-info">No hay historial registrado.</div>
                `);
                return;
            }

			// TABLA ADAPTADA A TU TABLA REAL
            let html = `
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Fecha / Hora</th>
                    		<th>Usuario</th>
                    		<th>Acción</th>
                    		<th>Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
			// RECORRER EL HISTORIAL Y CREAR FILAS
            r.data.forEach(h => {
                html += `
                    <tr>
                        <td>${h.fecha_hora}</td>
                		<td>${h.usuario}</td>
                		<td>${h.accion}</td>
                		<td>${h.detalle || '-'}</td>
                    </tr>
                `;
            });

            html += `</tbody></table>`;

            $("#contenedorHistorial").html(html);
        },
		//	MANEJO DE ERRORES AJAX (COMUNICACIÓN)
        error: function (xhr) {
            console.log("ERROR AJAX HISTORIAL:", xhr.responseText);
            $("#contenedorHistorial").html(`
                <div class="alert alert-danger">Error de comunicación con el servidor.</div>
            `);
        }
    });

});

// 6) EXPORTAR A EXCEL
$(document).on("click", "#btnExportarExcel", function () {

    let fecha   = $("#filtro_fecha").val();
    let empresa = $("#filtro_empresa").val();

    window.open(
        '/modulos/asistencias/acciones/exportar_reporte_excel.php?fecha=' + fecha + '&empresa=' + empresa,
        '_blank'
    );
});

	// 7) TOGGLE VISTA COMPACTA / DETALLADA
	$(document).on("click", "#btnToggleVista", function () {
    	vistaCompacta = !vistaCompacta;

    	if (vistaCompacta) {
			$(this).html('<i class="fa fa-list"></i> Vista Completa');
    		} 
		else {
        	$(this).html('<i class="fa fa-list"></i> Vista Compacta');
    		}
    	cargarReporte(); // recargar tabla con la nueva vista
		});

