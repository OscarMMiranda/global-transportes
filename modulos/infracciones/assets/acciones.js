// archivo: /modulos/infracciones/assets/accion.js
// Acciones principales del módulo Infracciones

const DEBUG_INF = true;

function ilog(msg) {
    if (DEBUG_INF) console.log("INF:", msg);
}

function ierror(msg) {
    if (DEBUG_INF) console.error("INF ERROR:", msg);
}

ilog("acciones.js cargado correctamente");

/* ============================================================
   LIMPIAR MODAL EDITAR CUANDO SE CIERRA
   ============================================================ */
$("#modalEditar").on("hidden.bs.modal", function () {
    $("#editar_id").val('');
    $("#editar_codigo").val('');
    $("#editar_descripcion").val('');
    $("#editar_gravedad").val('');
    $("#editar_puntos").val('');
    $("#editar_porcentaje_uit").val('');
    $("#editar_entidad_emisora_id").val('');
});

/* ============================================================
   LIMPIAR MODAL CREAR CUANDO SE CIERRA
   ============================================================ */
$("#modalCrear").on("hidden.bs.modal", function () {
    $("#formCrearInfraccion")[0].reset();
});

/* ============================================================
   LIMPIAR MODAL ELIMINAR CUANDO SE CIERRA
   ============================================================ */
$("#modalEliminar").on("hidden.bs.modal", function () {

    $("#eliminar_id").val('');

    $("#eliminar_codigo").text('');
    $("#eliminar_descripcion").text('');
    $("#eliminar_gravedad").text('');
    $("#eliminar_puntos").text('');
    $("#eliminar_porcentaje_uit").text('');
    $("#eliminar_monto_base").text('');
    $("#eliminar_entidad_emisora").text('');

    $("#eliminar_creado_por").text('');
    $("#eliminar_fecha_creacion").text('');
    $("#eliminar_modificado_por").text('');
    $("#eliminar_fecha_modificacion").text('');
});

/* ============================================================
   ACCIÓN: EDITAR INFRACCIÓN
   ============================================================ */
function editarInfraccion(id) {
    ilog("Editar infracción ID: " + id);

    $.post("ajax/obtener.php", { id: id }, function (data) {

        if (!data) {
            Swal.fire("Error", "No se encontró la infracción", "error");
            return;
        }

        // Llenar formulario EDITAR
        $("#editar_id").val(data.id);
        $("#editar_codigo").val(data.codigo);
        $("#editar_descripcion").val(data.descripcion);
        $("#editar_gravedad").val(data.gravedad);
        $("#editar_puntos").val(data.puntos);
        $("#editar_porcentaje_uit").val(data.porcentaje_uit);
        $("#editar_entidad_emisora_id").val(data.entidad_emisora_id);

        // ABRIR MODAL
        $("#modalEditar").modal("show");

    }, "json").fail(function (xhr) {
        console.error("ERROR AJAX:", xhr.responseText);
    });
}

/* ============================================================
   ACCIÓN: ELIMINAR INFRACCIÓN
   ============================================================ */
function eliminarInfraccion(id) {
    ilog("Eliminar infracción ID: " + id);

    $.post("ajax/obtener.php", { id: id }, function (data) {

        if (!data) {
            Swal.fire("Error", "No se encontró la infracción", "error");
            return;
        }

        $("#eliminar_id").val(data.id);

        $("#eliminar_codigo").text(data.codigo);
        $("#eliminar_descripcion").text(data.descripcion);
        $("#eliminar_gravedad").text(data.gravedad);
        $("#eliminar_puntos").text(data.puntos);

        $("#eliminar_porcentaje_uit").text(data.porcentaje_uit + "%");
        $("#eliminar_monto_base").text("S/ " + data.monto_base);

        $("#eliminar_entidad_emisora").text(data.entidad_nombre);

        $("#eliminar_creado_por").text(data.creado_por);
        $("#eliminar_fecha_creacion").text(data.fecha_creacion);
        $("#eliminar_modificado_por").text(data.modificado_por);
        $("#eliminar_fecha_modificacion").text(data.fecha_modificacion);

        $("#modalEliminar").modal("show");

    }, "json");
}

/* ============================================================
   SUBMIT: CREAR
   ============================================================ */
$(document).on("submit", "#formCrearInfraccion", function (e) {
    e.preventDefault();

    $.post("ajax/guardar.php", $(this).serialize(), function (res) {

        if (res.ok) {
            Swal.fire("Éxito", "Infracción registrada correctamente", "success");
            $("#modalCrear").modal("hide");
            recargarTabla();
        } else {
            Swal.fire("Error", res.msg || "No se pudo guardar", "error");
        }

    }, "json");
});

/* ============================================================
   SUBMIT: EDITAR
   ============================================================ */
$(document).on("submit", "#formEditarInfraccion", function (e) {
    e.preventDefault();

    $.post("ajax/actualizar.php", $(this).serialize(), function (res) {

        if (res.ok) {
            Swal.fire("Actualizado", "Infracción actualizada correctamente", "success");
            $("#modalEditar").modal("hide");
            recargarTabla();
        } else {
            Swal.fire("Error", res.msg || "No se pudo actualizar", "error");
        }

    }, "json");
});

/* ============================================================
   RECARGAR DATATABLES SIN REINICIALIZAR
   ============================================================ */
function recargarTabla() {
    if ($.fn.DataTable.isDataTable("#tablaInfracciones")) {
        $("#tablaInfracciones").DataTable().ajax.reload(null, false);
    }
}
