// archivo: /modulos/mantenimiento/tipo_vehiculo/js/editar.js
console.log("📦 editar.js inicializado");

$("#editar_tv_nombre").on("input", function () {
    if ($(this).val().trim() !== "") {
        $(this).removeClass("is-invalid");
    }
});

$("#editar_tv_categoria_id").on("change", function () {
    if ($(this).val() !== "") {
        $(this).removeClass("is-invalid");
    }
});

$(document).on("click", "#btnActualizarTipoVehiculo", function () {

    let id = $("#editar_tv_id").val();
    let nombre = $("#editar_tv_nombre").val().trim();
    let descripcion = $("#editar_tv_descripcion").val().trim();
    let categoria_id = $("#editar_tv_categoria_id").val();

    let valido = true;

    if (nombre === "") {
        $("#editar_tv_nombre").addClass("is-invalid");
        valido = false;
    }

    if (categoria_id === "") {
        $("#editar_tv_categoria_id").addClass("is-invalid");
        valido = false;
    }

    if (!valido) return;

    $.ajax({
        url: "/modulos/mantenimiento/tipo_vehiculo/acciones/actualizar.php",
        type: "POST",
        data: {
            id: id,
            nombre: nombre,
            descripcion: descripcion,
            categoria_id: categoria_id
        },
        dataType: "json",
        success: function (resp) {
            if (resp.ok) {
                notifySuccess("Actualizado", resp.msg);
                $("#modalEditarTipoVehiculo").modal("hide");

                if (typeof tablaActivos !== "undefined") {
                    tablaActivos.ajax.reload(null, false);
                }
                if (typeof tablaInactivos !== "undefined") {
                    tablaInactivos.ajax.reload(null, false);
                }
            } else {
                notifyWarning("Atención", resp.msg);
            }
        },
        error: function () {
            notifyError("Error", "No se pudo actualizar el registro.");
        }
    });
});