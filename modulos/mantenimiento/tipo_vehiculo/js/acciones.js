// archivo: /modulos/mantenimiento/tipo_vehiculo/js/acciones.js
	console.log("📦 acciones.js inicializado");

// ---------------------------------------------------------
// FUNCIÓN GENERAL PARA CONFIRMAR Y EJECUTAR UNA ACCIÓN
// ---------------------------------------------------------
function confirmarAccion(titulo, mensaje, callback) {

	$("#tituloConfirmacion").text(titulo);
	$("#mensajeConfirmacion").text(mensaje);

	$("#modalConfirmacion").modal("show");

    // Evitar múltiples bindings
    $("#btnConfirmarAccion").off("click");

    $("#btnConfirmarAccion").on("click", function () {
        $("#modalConfirmacion").modal("hide");
        callback();
    });
}

// ---------------------------------------------------------
// DESACTIVAR
// ---------------------------------------------------------
$(document).on("click", ".btn-desactivar", function () {
    var id = $(this).data("id");

    confirmarAccion(
        "Desactivar Tipo de Vehículo",
        "¿Está seguro de desactivar este registro?",
        function () {

            $.ajax({
                url: "/modulos/mantenimiento/tipo_vehiculo/acciones/desactivar.php",
                type: "POST",
                data: { id: id },
                dataType: "json",

                success: function (r) {
                    if (r.ok) {
                        notifySuccess("Desactivado", r.msg);

                        if (typeof tablaActivos !== "undefined") {
                            tablaActivos.ajax.reload(null, false);
                        }
                        if (typeof tablaInactivos !== "undefined") {
                            tablaInactivos.ajax.reload(null, false);
                        }

                    } else {
                        notifyWarning("Atención", r.msg);
                    }
                },

                error: function () {
                    notifyError("Error", "No se pudo desactivar el registro.");
                }
            });
        }
    );
});

// ---------------------------------------------------------
// RESTAURAR
// ---------------------------------------------------------
$(document).on("click", ".btn-restaurar", function () {
    var id = $(this).data("id");

    confirmarAccion(
        "Restaurar Tipo de Vehículo",
        "¿Desea restaurar este registro?",
        function () {

            $.ajax({
                url: "/modulos/mantenimiento/tipo_vehiculo/acciones/restaurar.php",
                type: "POST",
                data: { id: id },
                dataType: "json",

                success: function (r) {
                    if (r.ok) {
                        notifySuccess("Restaurado", r.msg);

                        if (typeof tablaActivos !== "undefined") {
                            tablaActivos.ajax.reload(null, false);
                        }
                        if (typeof tablaInactivos !== "undefined") {
                            tablaInactivos.ajax.reload(null, false);
                        }

                    } else {
                        notifyWarning("Atención", r.msg);
                    }
                },

                error: function () {
                    notifyError("Error", "No se pudo restaurar el registro.");
                }
            });
        }
    );
});

// ---------------------------------------------------------
// ELIMINAR (solo si lo habilitas en backend)
// ---------------------------------------------------------
$(document).on("click", ".btn-eliminar", function () {
    var id = $(this).data("id");

    confirmarAccion(
        "Eliminar Tipo de Vehículo",
        "Esta acción es permanente. ¿Desea continuar?",
        function () {

            $.ajax({
                url: "/modulos/mantenimiento/tipo_vehiculo/acciones/eliminar.php",
                type: "POST",
                data: { id: id },
                dataType: "json",

                success: function (r) {
                    if (r.ok) {
                        notifySuccess("Eliminado", r.msg);

                        if (typeof tablaActivos !== "undefined") {
                            tablaActivos.ajax.reload(null, false);
                        }
                        if (typeof tablaInactivos !== "undefined") {
                            tablaInactivos.ajax.reload(null, false);
                        }

                    } else {
                        notifyWarning("Atención", r.msg);
                    }
                },

                error: function () {
                    notifyError("Error", "No se pudo eliminar el registro.");
                }
            });
        }
    );
});

// ---------------------------------------------------------
// VER DETALLE
// ---------------------------------------------------------
$(document).on("click", ".btn-ver", function () {
    var id = $(this).data("id");
    cargarDetalle(id);
});

// ---------------------------------------------------------
// FUNCIÓN PARA CARGAR DETALLE EN EL MODAL
// ---------------------------------------------------------
function cargarDetalle(id) {
    $.ajax({
        url: "/modulos/mantenimiento/tipo_vehiculo/acciones/ver.php",
        type: "POST",
        data: { id: id },
        dataType: "json",

        success: function (resp) {
            if (resp.ok) {

                $("#ver_tv_id").text(resp.data.id);
                $("#ver_tv_nombre").text(resp.data.nombre);
                $("#ver_tv_descripcion").html(resp.data.descripcion);
                $("#ver_tv_estado").html(resp.data.estado);

                $("#modalVerTipoVehiculo").modal("show");

            } else {
                notifyWarning("Atención", resp.msg);
            }
        },

        error: function () {
            notifyError("Error", "No se pudo cargar el detalle.");
        }
    });
}