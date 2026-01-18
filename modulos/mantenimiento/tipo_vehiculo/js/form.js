// archivo: /modulos/mantenimiento/tipo_vehiculo/js/form.js
console.log("📦 form.js inicializado");

// ---------------------------------------------------------
// GUARDAR / ACTUALIZAR TIPO DE VEHÍCULO
// ---------------------------------------------------------
$("#formTipoVehiculo").on("submit", function (e) {
    e.preventDefault();

    var formData = $(this).serialize();

    $.ajax({
        url: "/modulos/mantenimiento/tipo_vehiculo/acciones/guardar.php",
        type: "POST",
        data: formData,
        dataType: "json",

        success: function (r) {

            if (r.ok) {

                notifySuccess("Éxito", r.msg);

                // Cerrar modal
                $("#modalTipoVehiculo").modal("hide");

                // Recargar DataTables
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
            notifyError("Error", "No se pudo procesar la solicitud.");
        }
    });
});