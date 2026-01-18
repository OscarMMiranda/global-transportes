// archivo: /modulos/mantenimiento/tipo_vehiculo/js/modal.js
console.log("📦 modal.js inicializado");

// Cargar categorías (reutilizable)
function cargarCategoriasEnSelect($select, callback) {
    $.ajax({
        url: "/modulos/mantenimiento/tipo_vehiculo/acciones/categorias.php",
        type: "GET",
        dataType: "json",
        success: function (r) {
            $select.empty();

            if (!r.ok || !r.data || r.data.length === 0) {
                $select.append('<option value="">No hay categorías disponibles</option>');
                if (typeof callback === "function") callback(false);
                return;
            }

            $select.append('<option value="">Seleccione una categoría</option>');
            r.data.forEach(function (cat) {
                $select.append('<option value="' + cat.id + '">' + cat.nombre + '</option>');
            });

            if (typeof callback === "function") callback(true);
        },
        error: function () {
            $select.empty().append('<option value="">Error al cargar categorías</option>');
            if (typeof callback === "function") callback(false);
        }
    });
}

// NUEVO
$(document).on("click", "#btnNuevoTipoVehiculo", function () {

    $("#crear_tv_nombre").val("").removeClass("is-invalid");
    $("#crear_tv_descripcion").val("");
    $("#crear_tv_categoria_id").val("").removeClass("is-invalid");
    $("#alertSinCategorias").addClass("d-none");
    $("#btnGuardarTipoVehiculo").prop("disabled", false);

    cargarCategoriasEnSelect($("#crear_tv_categoria_id"), function (hayCategorias) {
        if (!hayCategorias) {
            $("#alertSinCategorias").removeClass("d-none");
            $("#btnGuardarTipoVehiculo").prop("disabled", true);
        }
    });

    $("#modalCrearTipoVehiculo").modal("show");
});

// EDITAR
$(document).on("click", ".btn-editar", function () {
    var id = $(this).data("id");

    $.ajax({
        url: "/modulos/mantenimiento/tipo_vehiculo/acciones/obtener.php",
        type: "POST",
        data: { id: id },
        dataType: "json",
        success: function (r) {
            if (!r.ok) {
                notifyError("No se pudo cargar el registro", r.msg);
                return;
            }

            $("#editar_tv_id").val(r.data.id);
            $("#editar_tv_nombre").val(r.data.nombre).removeClass("is-invalid");
            $("#editar_tv_descripcion").val(r.data.descripcion);
            $("#editar_tv_creado_por").text(r.data.creado_por_nombre || r.data.creado_por);
            $("#editar_tv_fecha_creado").text(r.data.fecha_creado || "");
            $("#editar_tv_fecha_modificacion").text(r.data.fecha_modificacion || "-");

            cargarCategoriasEnSelect($("#editar_tv_categoria_id"), function () {
                $("#editar_tv_categoria_id").val(r.data.categoria_id).removeClass("is-invalid");
            });

            $("#modalEditarTipoVehiculo").modal("show");
        },
        error: function () {
            notifyError("Error de comunicación", "No se pudo obtener la información.");
        }
    });
});

// VER
$(document).on("click", ".btn-ver", function () {
    var id = $(this).data("id");

    $.ajax({
        url: "/modulos/mantenimiento/tipo_vehiculo/acciones/ver.php",
        type: "POST",
        data: { id: id },
        dataType: "json",
        success: function (r) {
            if (!r.ok) {
                notifyError("No se pudo cargar el detalle", r.msg);
                return;
            }

            $("#ver_tv_id").text(r.data.id);
            $("#ver_tv_nombre").text(r.data.nombre);
            $("#ver_tv_descripcion").text(r.data.descripcion || "");
            $("#ver_tv_categoria").text(r.data.categoria_nombre || "");
            $("#ver_tv_estado").html(r.data.estado_badge || "");
            $("#ver_tv_creado_por").text(r.data.creado_por_nombre || r.data.creado_por);
            $("#ver_tv_fecha_creado").text(r.data.fecha_creado || "");
            $("#ver_tv_fecha_modificacion").text(r.data.fecha_modificacion || "-");

            $("#modalVerTipoVehiculo").modal("show");
        },
        error: function () {
            notifyError("Error de comunicación", "No se pudo obtener el detalle.");
        }
    });
});