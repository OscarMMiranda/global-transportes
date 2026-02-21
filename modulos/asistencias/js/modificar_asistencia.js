/*
    archivo: /modulos/asistencias/js/modificar_asistencia.js
    módulo: asistencias → submódulo modificar
*/

console.log("JS MODIFICAR ASISTENCIA CARGADO");

// ============================================================
// TOAST CORPORATIVO
// ============================================================
function toastSuccess(msg) {
    let toastEl = document.getElementById("toastSuccess");
    toastEl.querySelector(".toast-body").innerHTML = msg;

    let t = new bootstrap.Toast(toastEl);
    t.show();
}

// ============================================================
// ABRIR MODAL Y CARGAR DATOS
// ============================================================
$(document).on("click", ".btnEditarAsistencia", function () {

    let id = $(this).data("id");

    console.log("EDITAR ASISTENCIA ID:", id);

    $.post("/modulos/asistencias/ajax/get_asistencias.php",
        { id: id },
        function (r) {

            if (!r.ok) {
                alert("Error: " + r.error);
                return;
            }

            let d = r.data;

            // Campos ocultos
            $("#asistencia_id").val(d.id);
            $("#empresa_id_hidden").val(d.empresa_id);
            $("#conductor_id_hidden").val(d.conductor_id);

            // Campos visibles
            $("#empresa_id_edit").val(d.empresa);
            $("#conductor_id_edit").val(d.conductor);

            // Tipo (cargar dinámicamente)
            $("#codigo_tipo_edit").html("");
            d.tipos.forEach(t => {
                let sel = (t.codigo === d.tipo_codigo) ? "selected" : "";
                $("#codigo_tipo_edit").append(
                    `<option value="${t.codigo}" ${sel}>${t.descripcion}</option>`
                );
            });

            // Fecha
            $("#fecha_edit").val(d.fecha);

            // Feriado
            $("#es_feriado_edit").val(d.es_feriado ? "Sí" : "No");

            // Horas
            $("#hora_entrada_edit").val(d.hora_entrada);
            $("#hora_salida_edit").val(d.hora_salida);

            // Observación
            $("#observacion_edit").val(d.observacion);

            // Abrir modal
            $("#modalModificarAsistencia").modal("show");
        },
        "json"
    );
});

// ============================================================
// GUARDAR CAMBIOS
// ============================================================
$(document).on("click", "#btnGuardarCambiosAsistencia", function () {

    let payload = {
        // id: $("#asistencia_id").val(),
        asistencia_id: $("#asistencia_id").val(),

        codigo_tipo: $("#codigo_tipo_edit").val(),
        fecha: $("#fecha_edit").val(),
        hora_entrada: $("#hora_entrada_edit").val(),
        hora_salida: $("#hora_salida_edit").val(),
        observacion: $("#observacion_edit").val()
    };

    console.log("GUARDANDO CAMBIOS:", payload);

    $.post("/modulos/asistencias/acciones/modificar.php",
        payload,
        function (r) {

            if (!r.ok) {
                alert("Error: " + r.error);
                return;
            }

            // Cerrar modal
            $("#modalModificarAsistencia").modal("hide");

             // Toast
            toastSuccess("Asistencia actualizada correctamente");

            // Refrescar tabla
            $("#btnBuscar").click();
        },
        "json"
    );
});

