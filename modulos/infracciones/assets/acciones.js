// archivo: /modulos/infracciones/assets/acciones.js
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
   EJECUTAR SOLO CUANDO JQUERY YA ESTÁ DISPONIBLE
   ============================================================ */
document.addEventListener("DOMContentLoaded", function () {

    if (typeof $ === "undefined") {
        console.error("ERROR: jQuery NO está cargado antes de acciones.js");
        return;
    }

    /* ============================================================
       LIMPIAR MODAL VER CUANDO SE CIERRA
       ============================================================ */
    $("#modalVer").on("hidden.bs.modal", function () {
        $("#ver_codigo").text('');
        $("#ver_descripcion").text('');
        $("#ver_gravedad").text('');
        $("#ver_puntos").text('');
        $("#ver_porcentaje_uit").text('');
        $("#ver_monto_base").text('');
        $("#ver_entidad_emisora").text('');
        $("#ver_creado_por").text('');
        $("#ver_fecha_creacion").text('');
        $("#ver_modificado_por").text('');
        $("#ver_fecha_modificacion").text('');
    });

    /* ============================================================
       ACCIÓN: VER INFRACCIÓN
       ============================================================ */
    window.verInfraccion = function(id) {

        ilog("Ver infracción ID: " + id);

        $.post("ajax/obtener.php", { id: id }, function(data){

            if (!data) {
                Swal.fire("Error", "No se encontró la infracción", "error");
                return;
            }

            // Llenar modal VER
            $("#ver_codigo").text(data.codigo);
            $("#ver_descripcion").text(data.descripcion);
            $("#ver_gravedad").text(data.gravedad);
            $("#ver_puntos").text(data.puntos);
            $("#ver_porcentaje_uit").text(data.porcentaje_uit + "%");
            $("#ver_monto_base").text("S/ " + data.monto_base);
            $("#ver_entidad_emisora").text(data.entidad_nombre);

            $("#ver_creado_por").text(data.creado_por);
            $("#ver_fecha_creacion").text(data.fecha_creacion);
            $("#ver_modificado_por").text(data.modificado_por);
            $("#ver_fecha_modificacion").text(data.fecha_modificacion);

            // Mostrar modal
            $("#modalVer").modal("show");

        }, "json").fail(function(xhr){
            console.error("ERROR AJAX VER:", xhr.responseText);
            Swal.fire("Error", "No se pudo obtener la infracción", "error");
        });
    };

    /* ============================================================
       ACCIÓN: EDITAR INFRACCIÓN
       ============================================================ */
    window.editarInfraccion = function (id) {
        ilog("Editar infracción ID: " + id);

        $.post("ajax/obtener.php", { id: id }, function (data) {

            if (!data) {
                Swal.fire("Error", "No se encontró la infracción", "error");
                return;
            }

            $("#editar_id").val(data.id);
            $("#editar_codigo").val(data.codigo);
            $("#editar_descripcion").val(data.descripcion);
            $("#editar_gravedad").val(data.gravedad);
            $("#editar_puntos").val(data.puntos);
            $("#editar_porcentaje_uit").val(data.porcentaje_uit);
            $("#editar_entidad_emisora_id").val(data.entidad_emisora_id);

            $("#modalEditar").modal("show");

        }, "json").fail(function (xhr) {
            console.error("ERROR AJAX:", xhr.responseText);
        });
    };

    /* ============================================================
       ACCIÓN: ELIMINAR INFRACCIÓN
       ============================================================ */
    window.eliminarInfraccion = function (id) {
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
    };

    /* ============================================================
       ACCIÓN: REACTIVAR / RECUPERAR INFRACCIÓN
       ============================================================ */
    window.reactivarInfraccion = function(id) {

        Swal.fire({
            title: "¿Recuperar infracción?",
            text: "La infracción será reactivada y volverá a estar disponible.",
            icon: "info",
            showCancelButton: true,
            confirmButtonText: "Sí, recuperar"
        }).then(function(result){

            if (!result.isConfirmed) return;

            $.post("ajax/reactivar.php", { id: id }, function(res){

                if (res.ok) {
                    Swal.fire("Recuperada", res.msg, "success");

                    // ESTA TABLA NO USA AJAX → SE RECARGA LA PÁGINA
                    location.reload();

                } else {
                    Swal.fire("Error", res.msg || "No se pudo recuperar", "error");
                }

            }, "json").fail(function(xhr){
                console.error("ERROR AJAX RECUPERAR:", xhr.responseText);
                Swal.fire("Error", "No se pudo recuperar la infracción", "error");
            });

        });
    };

    /* ============================================================
       RECARGAR DATATABLES SIN REINICIALIZAR
       ============================================================ */
    window.recargarTabla = function () {
        if ($.fn.DataTable.isDataTable("#tablaInfracciones")) {
            $("#tablaInfracciones").DataTable().ajax.reload(null, false);
        }
    };

    /* ============================================================
       EVENTOS DELEGADOS (SIN onclick)
       ============================================================ */

    $(document).on("click", ".btnVerInfraccion", function () {
        verInfraccion($(this).data("id"));
    });

    $(document).on("click", ".btnEditarInfraccion", function () {
        editarInfraccion($(this).data("id"));
    });

    $(document).on("click", ".btnEliminarInfraccion", function () {
        eliminarInfraccion($(this).data("id"));
    });

});
