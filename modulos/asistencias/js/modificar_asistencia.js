/*
    archivo: /modulos/asistencias/js/modificar_asistencia.js
    módulo: asistencias
*/

console.log("modificar_asistencia.js CARGADO REALMENTE");

document.addEventListener("DOMContentLoaded", function () {

    if (!document.getElementById('modalModificarAsistencia')) {
        console.log("modificar_asistencia.js: no aplica en esta pantalla");
        return;
    }

    console.log("modificar_asistencia.js ACTIVO");

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

    // ============================================================
    // CARGAR DATOS EN EL MODAL
    // ============================================================
    $(document).on("click", ".btnEditarAsistencia", function () {

        let asistencia_id = $(this).data("id");
        console.log("EDITAR ASISTENCIA ID:", asistencia_id);

        $("#asistencia_id").val(asistencia_id);
        $("#alertaModificarAsistencia").html("");

        $.post('../acciones/obtener_asistencia.php', { id: asistencia_id }, function (r) {

            if (!r.ok) {
                mostrarAlertaEditar("danger", r.error || "No se pudo cargar la asistencia");
                return;
            }

            let datos = r.data;

            $("#empresa_id_hidden").val(datos.empresa_id);
            $("#conductor_id_hidden").val(datos.conductor_id);

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

                $("#empresa_id_edit").val(datos.empresa_nombre);
                $("#conductor_id_edit").val(datos.conductor);
                $("#fecha_edit").val(datos.fecha);
                $("#hora_entrada_edit").val(datos.hora_entrada);
                $("#hora_salida_edit").val(datos.hora_salida);
                $("#observacion_edit").val(datos.observacion);
                $("#es_feriado_edit").val(datos.es_feriado == 1 ? "Sí" : "No");

                const modal = new bootstrap.Modal(
                    document.getElementById('modalModificarAsistencia')
                );
                modal.show();

            }, 'json');

        }, 'json')
        .fail(function () {
            mostrarAlertaEditar("danger", "Error de comunicación con el servidor");
        });

    });

});
