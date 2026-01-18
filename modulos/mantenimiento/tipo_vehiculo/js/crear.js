// archivo: /modulos/mantenimiento/tipo_vehiculo/js/crear.js
console.log("📦 crear.js inicializado");

$("#crear_tv_nombre").on("input", function () {
    if ($(this).val().trim() !== "") {
        $(this).removeClass("is-invalid");
    }
});

$("#crear_tv_categoria_id").on("change", function () {
    if ($(this).val() !== "") {
        $(this).removeClass("is-invalid");
    }
});

$(document).on("click", "#btnGuardarTipoVehiculo", function () {

    let nombre = $("#crear_tv_nombre").val().trim();
    let descripcion = $("#crear_tv_descripcion").val().trim();
    let categoria_id = $("#crear_tv_categoria_id").val();

    let valido = true;

    if (nombre === "") {
        $("#crear_tv_nombre").addClass("is-invalid");
        valido = false;
    }

    if (categoria_id === "") {
        $("#crear_tv_categoria_id").addClass("is-invalid");
        valido = false;
    }

    if (!valido) return;

    $.ajax({
        url: "/modulos/mantenimiento/tipo_vehiculo/acciones/guardar.php",
        type: "POST",
        data: {
            nombre: nombre,
            descripcion: descripcion,
            categoria_id: categoria_id
        },
        dataType: "json",
        success: function (resp) {
            if (resp.ok) {
                notifySuccess("Guardado", resp.msg);
                $("#modalCrearTipoVehiculo").modal("hide");

                if (typeof tablaActivos !== "undefined") {
                    tablaActivos.ajax.reload(null, false);
                }
            } else {
                notifyWarning("Atención", resp.msg);
            }
        },
        error: function () {
            notifyError("Error", "No se pudo guardar el registro.");
        }
    });
});