// archivo: /modulos/vehiculos/js/form.js

$(document).on("submit", "#formVehiculo", function (e) {
    e.preventDefault();

    const form = $(this);
    const datos = form.serialize();

    $.ajax({
        url: "/modulos/vehiculos/acciones/crear.php",
        type: "POST",
        data: datos,
        dataType: "json",

        success: function (r) {
            console.log("Respuesta del servidor:", r);

            if (r.ok) {
                alert("Vehículo registrado correctamente");
                $("#modalVehiculo").modal("hide");
                $("#tblActivosVehiculos").DataTable().ajax.reload(null, false);
            } else {
                alert("Error al guardar: " + (r.error_sql ?? r.msg));
            }
        },

        error: function (xhr) {
            console.error("❌ Error AJAX:", xhr.responseText);

            try {
                const r = JSON.parse(xhr.responseText);
                alert("Error al guardar: " + (r.error_sql ?? "Error desconocido"));
            } catch (e) {
                alert("Error al guardar (respuesta no válida)");
            }
        }
    });
});