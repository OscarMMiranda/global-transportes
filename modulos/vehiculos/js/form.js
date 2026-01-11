// archivo: /modulos/vehiculos/js/form.js

console.log("📝 form.js inicializado");

// ---------------------------------------------------------
// GUARDAR FORMULARIO (Nuevo o Editar)
// ---------------------------------------------------------
$(document).on("submit", "#formVehiculo", function (e) {
    e.preventDefault();

    const datos = $(this).serialize();

    // Detectar si es edición
    const esEdicion = $(this).find("input[name='id']").length > 0;

    // Endpoint según modo
    const url = esEdicion
        ? "/modulos/vehiculos/acciones/editar.php"
        : "/modulos/vehiculos/acciones/crear.php";

    // Spinner mientras guarda
    $("#modalVehiculoBody").append(`
        <div id="savingOverlay" class="text-center py-3">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2">Guardando...</p>
        </div>
    `);

    $.post(url, datos, function (resp) {

        $("#savingOverlay").remove();

        alert(resp.msg);

        if (resp.ok) {

            // Cerrar modal
            $("#modalVehiculo").modal("hide");

            // Recargar tablas
            if (esEdicion) {
                VehiculosDT.reloadActivos();
                VehiculosDT.reloadInactivos();
            } else {
                VehiculosDT.reloadActivos();
            }
        }

    }, "json")
    .fail(function (xhr) {
        $("#savingOverlay").remove();
        console.log("❌ Error AJAX:", xhr.responseText);
        alert("Error al guardar los datos del vehículo.");
    });
});