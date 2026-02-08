/*
    archivo: /modulos/asistencias/js/modificar_asistencia.js
    propósito: manejar el flujo completo de modificación de asistencia
*/

console.log("modificar_asistencia.js CARGADO REALMENTE");

document.addEventListener("DOMContentLoaded", function () {

    // Guard clause → solo aplica si existe el modal
    if (!document.getElementById('modalModificarAsistencia')) {
        console.log("modificar_asistencia.js: no aplica en esta pantalla");
        return;
    }

    console.log("modificar_asistencia.js ACTIVO");

    // ------------------------------------------------------------
    // ALERTA VISUAL
    // ------------------------------------------------------------
    function mostrarAlertaEditar(tipo, mensaje) {
        let html = `
            <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        document.getElementById('alertaModificarAsistencia').innerHTML = html;

        setTimeout(() => {
            let alerta = document.querySelector('#alertaModificarAsistencia .alert');
            if (alerta) alerta.remove();
        }, 4000);
    }

    // ------------------------------------------------------------
    // MARCAR CAMPO INVÁLIDO
    // ------------------------------------------------------------
    function marcarCampo(selector) {
        let el = document.querySelector(selector);
        if (!el) return;

        el.classList.add('is-invalid');
        setTimeout(() => el.classList.remove('is-invalid'), 2000);
    }

    // ------------------------------------------------------------
    // CARGAR DATOS EN EL MODAL
    // ------------------------------------------------------------
    $(document).on("click", ".btnEditarAsistencia", function () {

        let asistencia_id = $(this).data("id");
        console.log("EDITAR ASISTENCIA ID:", asistencia_id);

        // Guardar ID en el input oculto
        $("#asistencia_id").val(asistencia_id);

        // Mostrar modal
        const modal = new bootstrap.Modal(
            document.getElementById('modalModificarAsistencia')
        );
        modal.show();

        // Limpiar alertas
        $("#alertaModificarAsistencia").html("");

        // Cargar datos vía AJAX
        $.post('../acciones/cargar_asistencia.php', {
            asistencia_id: asistencia_id
        }, function (r) {

            console.log("DATOS RECIBIDOS PARA EDITAR:", r);

            if (!r.ok) {
                mostrarAlertaEditar("danger", r.error || "No se pudo cargar la asistencia");
                return;
            }

            // Llenar campos
            $("#empresa_id_edit").val(r.data.empresa_id);
            $("#conductor_id_edit").html(`<option value="${r.data.conductor_id}">${r.data.conductor_nombre}</option>`);
            $("#codigo_tipo_edit").val(r.data.codigo_tipo);
            $("#fecha_edit").val(r.data.fecha);
            $("#hora_entrada_edit").val(r.data.hora_entrada);
            $("#hora_salida_edit").val(r.data.hora_salida);
            $("#observacion_edit").val(r.data.observacion);
            $("#es_feriado_edit").val(r.data.es_feriado == 1 ? "Sí" : "No");

        }, 'json')
        .fail(function (xhr) {
            console.log("ERROR AJAX cargar_asistencia:", xhr.responseText);
            mostrarAlertaEditar("danger", "Error de comunicación con el servidor");
        });

    });

    // ------------------------------------------------------------
    // GUARDAR CAMBIOS
    // ------------------------------------------------------------
    $(document).on("click", "#btnGuardarCambiosAsistencia", function () {

        console.log("GUARDAR CAMBIOS ASISTENCIA");

        // Validación
        if ($("#empresa_id_edit").val() === "") {
            mostrarAlertaEditar("warning", "Seleccione una empresa");
            marcarCampo("#empresa_id_edit");
            return;
        }

        if ($("#conductor_id_edit").val() === "") {
            mostrarAlertaEditar("warning", "Seleccione un conductor");
            marcarCampo("#conductor_id_edit");
            return;
        }

        if ($("#codigo_tipo_edit").val() === "") {
            mostrarAlertaEditar("warning", "Seleccione un tipo");
            marcarCampo("#codigo_tipo_edit");
            return;
        }

        if ($("#fecha_edit").val() === "") {
            mostrarAlertaEditar("warning", "Seleccione una fecha");
            marcarCampo("#fecha_edit");
            return;
        }

        // Spinner
        let btn = $("#btnGuardarCambiosAsistencia");
        btn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm"></span> Guardando...');

        // AJAX
        $.post('../acciones/modificar.php', {
            asistencia_id: $("#asistencia_id").val(),
            empresa_id: $("#empresa_id_edit").val(),
            conductor_id: $("#conductor_id_edit").val(),
            codigo_tipo: $("#codigo_tipo_edit").val(),
            fecha: $("#fecha_edit").val(),
            hora_entrada: $("#hora_entrada_edit").val(),
            hora_salida: $("#hora_salida_edit").val(),
            observacion: $("#observacion_edit").val()
        }, function (r) {

            console.log("RESPUESTA modificar.php:", r);

            btn.prop("disabled", false).html("Guardar cambios");

            if (r.ok) {
                mostrarAlertaEditar("success", "Asistencia modificada correctamente");

                setTimeout(() => {
                    let modalEl = document.getElementById('modalModificarAsistencia');
                    let modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();
                }, 800);

                // Si tienes tabla, aquí refrescamos
                // cargarTablaAsistencias();

            } else {
                mostrarAlertaEditar("danger", r.error || "Error al modificar asistencia");
            }

        }, 'json')
        .fail(function (xhr) {
            console.log("ERROR AJAX modificar.php:", xhr.responseText);
            btn.prop("disabled", false).html("Guardar cambios");
            mostrarAlertaEditar("danger", "Error de comunicación con el servidor");
        });

    });

});
