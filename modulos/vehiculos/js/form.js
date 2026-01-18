// archivo: /modulos/vehiculos/js/form.js

$(document).on("submit", "#formVehiculo", function (e) {
    e.preventDefault();

    var form = $(this);
    var datos = form.serialize();

    // Detectar si es edición
    var esEdicion = form.find('input[name="id"]').length > 0;

    var url = esEdicion
        ? "/modulos/vehiculos/acciones/editar.php"
        : "/modulos/vehiculos/acciones/crear.php";

    $.ajax({
        url: url,
        type: "POST",
        data: datos,
        dataType: "json",

        success: function (r) {
            console.log("Respuesta del servidor:", r);

            if (r && r.ok) {
                notifySuccess(
                    esEdicion ? "Vehículo actualizado" : "Vehículo registrado",
                    r.msg || "La operación se completó correctamente."
                );

                $("#modalVehiculo").modal("hide");

                if ($("#tblActivosVehiculos").length) {
                    $("#tblActivosVehiculos").DataTable().ajax.reload(null, false);
                }
            } else {
                var msg = (r && r.msg) ? r.msg : "Error desconocido";
                notifyError(
                    "No se pudo guardar el vehículo",
                    msg
                );

                if (r && r.error_sql) {
                    console.error("SQL:", r.error_sql);
                }
            }
        },

        error: function (xhr) {
            console.error("❌ Error AJAX:", xhr.responseText);

            notifyError(
                "Error de comunicación",
                "No se pudo completar la operación. Intente nuevamente."
            );
        }
    });
});