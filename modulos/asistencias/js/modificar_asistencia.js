/*
    archivo: /modulos/asistencias/js/modificar_asistencia.js
    módulo: asistencias
*/

$(document).ready(function () {

    console.log("modificar_asistencia.js CARGADO REALMENTE");

    const modalEl = document.getElementById('modalModificarAsistencia');

    // Si el modal no existe en esta vista, no aplica
    if (!modalEl) {
        console.log("modificar_asistencia.js: no aplica en esta pantalla");
        return;
    }

    console.log("modificar_asistencia.js ACTIVO");

    // ============================================================
    // Instancia ÚNICA del modal (Bootstrap 5 ya está cargado aquí)
    // ============================================================
    const modalModificar = new bootstrap.Modal(modalEl);

    // ============================================================
    // Función para mostrar alertas dentro del modal
    // ============================================================
    function mostrarAlertaEditar(tipo, mensaje) {
        let html = `
            <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $("#alertaModificarAsistencia").html(html);

        setTimeout(() => {
            $("#alertaModificarAsistencia .alert").remove();
        }, 4000);
    }

    // ============================================================
    // CLICK EN BOTÓN EDITAR ASISTENCIA
    // ============================================================
    $(document).on("click", ".btnEditarAsistencia", function () {

        let asistencia_id = $(this).data("id");
        console.log("EDITAR ASISTENCIA ID:", asistencia_id);

        $("#asistencia_id").val(asistencia_id);
        $("#alertaModificarAsistencia").html("");

        // ============================================================
        // 1. Obtener datos de la asistencia
        // ============================================================
        $.post('../acciones/obtener_asistencia.php', { id: asistencia_id }, function (r) {

            if (!r.ok) {
                mostrarAlertaEditar("danger", r.error || "No se pudo cargar la asistencia");
                return;
            }

            let datos = r.data;

            $("#empresa_id_hidden").val(datos.empresa_id);
            $("#conductor_id_hidden").val(datos.conductor_id);

            // ============================================================
            // 2. Cargar tipos de asistencia
            // ============================================================
            $.post('../acciones/obtener_tipos.php', {}, function (t) {

                if (!t.ok) {
                    mostrarAlertaEditar("danger", "No se pudieron cargar los tipos");
                    return;
                }

                let html = "";
                t.data.forEach(function (item) {
                    html += `<option value="${item.codigo}">${item.descripcion}</option>`;
                });

                $("#codigo_tipo_edit").html(html);
                $("#codigo_tipo_edit").val(datos.codigo_tipo);

                // ============================================================
                // 3. Cargar datos en el formulario
                // ============================================================
                $("#empresa_id_edit").val(datos.empresa_nombre);
                $("#conductor_id_edit").val(datos.conductor);
                $("#fecha_edit").val(datos.fecha);
                $("#hora_entrada_edit").val(datos.hora_entrada);
                $("#hora_salida_edit").val(datos.hora_salida);
                $("#observacion_edit").val(datos.observacion);
                $("#es_feriado_edit").val(datos.es_feriado == 1 ? "Sí" : "No");

                // ============================================================
                // 4. Mostrar modal usando la instancia única
                // ============================================================
                modalModificar.show();

            }, 'json');

        }, 'json')
        .fail(function () {
            mostrarAlertaEditar("danger", "Error de comunicación con el servidor");
        });

    });

});
