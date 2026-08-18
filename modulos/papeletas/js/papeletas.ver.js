// archivo: /modulos/papeletas/js/papeletas.ver.js

/* ============================================================
   VER PAPELETA (MODO SOLO LECTURA)
   ============================================================ */
$(document).on("click", ".btnVerPapeleta", function() {

    var id = $(this).data("id");

    // Cargar selects igual que EDITAR
    $.when(
        cargarVehiculosSelect(),
        cargarConductoresSelect(),
        cargarEntidadEmisoraSelect()
    ).done(function() {

        // Obtener la papeleta
        $.post('/modulos/papeletas/acciones/obtener.php', { id: id }, function(res) {

            if (!res.success) {
                Swal.fire("Error", res.msg, "error");
                return;
            }

            var p = res.data;

            /* ============================================================
               LLENAR CAMPOS DEL MODAL
               ============================================================ */

            $("#edit_id").val(p.id);
            $("#edit_codigo_papeleta").val(p.codigo_papeleta);

            $("#edit_vehiculo_id").val(p.vehiculo_id);
            $("#edit_conductor_id").val(p.conductor_id);
            $("#edit_entidad_emisora_id").val(p.entidad_emisora_id);

            // cargar infracciones según entidad
            $.post('/modulos/infracciones/acciones/listar_por_entidad.php',
                { entidad_id: p.entidad_emisora_id },
                function(html){
                    $("#edit_infraccion_id").html(html);
                    $("#edit_infraccion_id").val(p.infraccion_id);
                }
            );

            $("#edit_fecha_infraccion").val(p.fecha_infraccion);
            $("#edit_fecha_notificacion").val(p.fecha_notificacion);
            $("#edit_fecha_vencimiento").val(p.fecha_vencimiento);

            $("#edit_lugar").val(p.lugar);
            $("#edit_descripcion").val(p.descripcion);
            $("#edit_monto").val(p.monto);

            /* ============================================================
               MODO VER (SOLO LECTURA)
               ============================================================ */

            // Deshabilitar solo campos, NO botones
            $("#formEditarPapeleta")
                .find("input:not(#btnActualizarPapeleta), select, textarea")
                .prop("disabled", true);

            // Ocultar botón actualizar
            $("#btnActualizarPapeleta").hide();

            // Asignar ID al botón de archivos
            $("#btnVerArchivosDesdeEditar").data("id", p.id);

            /* ============================================================
               ABRIR MODAL
               ============================================================ */

            $("#modalEditarPapeleta").modal("show");

        }, 'json');

    });

});


/* ============================================================
   RESTAURAR CAMPOS CUANDO SE PRESIONA EDITAR
   ============================================================ */
$(document).on("click", ".btnEditarPapeleta", function() {

    // habilitar campos
    $("#formEditarPapeleta input, #formEditarPapeleta select, #formEditarPapeleta textarea")
        .prop("disabled", false);

    // mostrar botón actualizar
    $("#btnActualizarPapeleta").show();
});


/* ============================================================
   BOTÓN PROFESIONAL PARA ABRIR EL MODAL DE ARCHIVOS
   ============================================================ */
$(document).on("click", "#btnVerArchivosDesdeEditar", function() {

    var id = $(this).data("id");

    // Simular clic en el botón original de archivos
    $(".btnVerArchivos[data-id='" + id + "']").click();
});
