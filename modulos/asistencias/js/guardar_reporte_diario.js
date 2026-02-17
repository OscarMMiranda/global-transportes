/*
    archivo: /modulos/asistencias/js/guardar_reporte_diario.js
    módulo: asistencias
    propósito: guardar edición desde el submódulo REPORTE DIARIO
*/

$(document).ready(function () {

    console.log("guardar_reporte_diario.js CARGADO");

    var RD = window.RD || {};
    window.RD = RD;

    // Escuchar el botón correcto
    $(document).on("click", "#btnGuardarCambiosAsistencia", function () {

        let payload = {
            asistencia_id: $("#asistencia_id").val(),
            codigo_tipo: $("#codigo_tipo_edit").val(),
            hora_entrada: $("#hora_entrada_edit").val(),
            hora_salida: $("#hora_salida_edit").val(),
            observacion: $("#observacion_edit").val()
        };

        $.post('/modulos/asistencias/acciones/modificar.php', payload, function (r) {

            if (!r.ok) {
                toastError(r.error || "No se pudo guardar la asistencia.");
                return;
            }

            toastSuccess("Asistencia actualizada correctamente.");

            const modalEl = document.getElementById('modalModificarAsistencia');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) modalInstance.hide();

            if (typeof RD.cargarReporte === "function") {
                RD.cargarReporte();
            }

        }, 'json')
        .fail(function (xhr) {
            toastError("Error de comunicación con el servidor.");
            console.log("ERROR AJAX:", xhr.responseText);
        });

    });

});
