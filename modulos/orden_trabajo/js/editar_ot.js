// ARCHIVO: /modulos/orden_trabajo/js/editar_ot.js

// ============================================================
// ABRIR MODAL EDITAR
// ============================================================
$("#tablaOT").on("click", ".btn-editar", function () {

    let id = $(this).data("id");

    $("#modalEditarOTBody").html("<div class='p-3 text-center'>Cargando...</div>");

    $.post("/modulos/orden_trabajo/controllers/EditarController.php", { id }, function (r) {

        if (!r.ok) {
            Swal.fire({ icon:"error", title:"Error", text:r.msg });
            return;
        }

        let d = r.data;

        // ============================
        // CAMPOS BASE
        // ============================
        $("#editar_id").val(d.id);
        $("#editar_numero_ot").val(d.numero_ot);
        $("#editar_semana_ot").val(d.semana_formateada);
        $("#editar_fecha").val(d.fecha);

        cargarSelect("#editar_cliente_id", r.clientes, d.cliente_id);
        cargarSelect("#editar_empresa_id", r.empresas, d.empresa_id);
        cargarSelect("#editar_tipo_ot", r.tipos_ot, d.tipo_ot_id);

        // ❌ ESTA LÍNEA SE ELIMINA
        // cargarSelect("#editar_estado_id", r.estados, d.estado_ot);

        // ============================
        // CAMPOS ESPECIALES
        // ============================
        $("#editar_oc_cliente").val(d.oc_cliente);
        $("#editar_numero_dam").val(d.numero_dam);
        $("#editar_numero_booking").val(d.numero_booking);
        $("#editar_otros").val(d.otros);

        // ============================
        // MOSTRAR / OCULTAR CAMPOS SEGÚN TIPO OT
        // ============================
        mostrarCamposEditar($("#editar_tipo_ot option:selected").text());

        $("#editar_tipo_ot").off("change").on("change", function () {
            mostrarCamposEditar($(this).find("option:selected").text());
        });

        $("#modalEditarOT").modal("show");

    }, "json");
});

// ============================================================
// GUARDAR CAMBIOS
// ============================================================
$(document).on("submit", "#formEditarOT", function (e) {
    e.preventDefault();

    $.post("/modulos/orden_trabajo/controllers/ActualizarController.php", $(this).serialize(), function (resp) {

        if (resp.ok) {
            $("#modalEditarOT").modal("hide");
            tablaOT.ajax.reload(null, false);

            Swal.fire({
                icon: 'success',
                title: 'Actualizado',
                text: resp.msg,
                confirmButtonColor: '#ffc107'
            });

        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: resp.msg,
                confirmButtonColor: '#dc3545'
            });
        }

    }, "json");
});
