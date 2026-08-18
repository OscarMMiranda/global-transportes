/* archivo: /modulos/papeletas/js/papeletas.editar.js */

/* ============================================================
   INSTANCIA GLOBAL DEL MODAL
   ============================================================ */
var modalEditar = new bootstrap.Modal(document.getElementById('modalEditarPapeleta'));

/* ============================================================
   LIMPIAR MODAL AL CERRAR
   ============================================================ */
$('#modalEditarPapeleta').on('hidden.bs.modal', function () {
    $("#formEditarPapeleta")[0].reset();
    $("#editar_papeleta_id").val("");
    $("#edit_id").val("");
    $("#id_archivo_papeleta").val(""); // 🔥 limpiar ID de archivo
    console.log("Modal editar limpiado");
});

/* ============================================================
   ABRIR MODAL DE EDICIÓN
   ============================================================ */
$(document).on("click", ".btnEditarPapeleta", function() {

    var id = $(this).data("id");

    $.when(
        cargarVehiculosSelect(),
        cargarConductoresSelect(),
        cargarEntidadEmisoraSelect()
    ).done(function() {

        $.post('/modulos/papeletas/acciones/obtener.php', { id: id }, function(res) {

            if (!res.success) {
                Swal.fire("Error", "No se pudo cargar la papeleta", "error");
                return;
            }

            var p = res.data;

            /* ============================================================
               BLOQUEAR EDICIÓN SI LA PAPELETA ESTÁ PAGADA
               ============================================================ */
            if (p.estado_id == 7) { // 7 = PAGADA
                Swal.fire("Papeleta pagada", "No se puede editar una papeleta pagada.", "warning");
                return;
            }

            /* ============================================================
               ASIGNAR CAMPOS
               ============================================================ */
            $("#edit_id").val(p.id);
            $("#editar_papeleta_id").val(p.id);

            // 🔥 NECESARIO PARA SUBIR ARCHIVOS
            $("#id_archivo_papeleta").val(p.id);

            $("#edit_vehiculo_id").val(p.vehiculo_id);
            $("#edit_conductor_id").val(p.conductor_id);
            $("#edit_entidad_emisora_id").val(p.entidad_emisora_id);
            $("#edit_codigo_papeleta").val(p.codigo_papeleta);

            $("#edit_fecha_infraccion").val(p.fecha_infraccion);
            $("#edit_fecha_notificacion").val(p.fecha_notificacion);
            $("#edit_fecha_vencimiento").val(p.fecha_vencimiento);

            $("#edit_lugar").val(p.lugar);
            $("#edit_descripcion").val(p.descripcion);
            $("#edit_monto").val(p.monto);

            /* ⭐ CLAVE: ASIGNAR ID AL BOTÓN DE ARCHIVOS */
            $("#btnVerArchivosDesdeEditar").data("id", p.id);

            /* ============================================================
               ABRIR MODAL
               ============================================================ */
            modalEditar.show();

            /* ============================================================
               CARGAR INFRACCIONES SEGÚN ENTIDAD ACTUAL
               ============================================================ */
            cargarInfraccionesPorEntidad(p.entidad_emisora_id).done(function() {
                $("#edit_infraccion_id").val(p.infraccion_id);
            });

        }, 'json');

    });

});

/* ============================================================
   RECARGAR INFRACCIONES AL CAMBIAR ENTIDAD EMISORA
   ============================================================ */
$(document).on("change", "#edit_entidad_emisora_id", function() {

    var entidad_id = $(this).val();

    // 🔥 LIMPIAR VALOR Y HTML ANTES DE CARGAR
    $("#edit_infraccion_id").val("");
    $("#edit_infraccion_id").html("<option value=''>Cargando...</option>");

    if (!entidad_id) {
        $("#edit_infraccion_id").html("<option value=''>-- Seleccione infracción --</option>");
        return;
    }

    cargarInfraccionesPorEntidad(entidad_id).done(function() {
        $("#edit_infraccion_id").val(""); // asegurar que no quede la anterior
    });

});

/* ============================================================
   GUARDAR CAMBIOS
   ============================================================ */
$(document).on("click", "#btnActualizarPapeleta", function() {

    $.post(
        '/modulos/papeletas/acciones/actualizar.php',
        $("#formEditarPapeleta").serialize(),
        function(res) {

            if (res.success) {

                Swal.fire("Actualizado", "La papeleta fue actualizada correctamente", "success");

                modalEditar.hide();
                cargarPapeletas();

            } else {
                Swal.fire("Error", res.msg, "error");
            }

        },
        'json'
    );

});
