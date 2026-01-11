// archivo: /modulos/vehiculos/js/acciones.js

console.log("⚙️ acciones.js inicializado");

// ---------------------------------------------------------
// VER VEHÍCULO (abre modal de lectura)
// ---------------------------------------------------------
$(document).on("click", ".btn-view", function () {

    const id = $(this).data("id");

    $("#modalVerVehiculoBody").html(`
        <div class="text-center py-5">
            <div class="spinner-border text-info"></div>
            <p class="mt-3">Cargando información...</p>
        </div>
    `);

    $("#modalVerVehiculo").modal("show");

    $("#modalVerVehiculoBody").load("/modulos/vehiculos/vistas/ver.php?id=" + id);
});


// ---------------------------------------------------------
// DESACTIVAR (Soft Delete)
// ---------------------------------------------------------
$(document).on("click", ".btn-soft-delete", function () {

    const id = $(this).data("id");

    if (!confirm("¿Desactivar este vehículo?")) return;

    $.post("/modulos/vehiculos/acciones/desactivar.php", { id }, function (resp) {

        alert(resp.msg);

        if (resp.ok) {
            VehiculosDT.reloadActivos();
            VehiculosDT.reloadInactivos();
        }

    }, "json");
});


// ---------------------------------------------------------
// RESTAURAR VEHÍCULO
// ---------------------------------------------------------
$(document).on("click", ".btn-restore", function () {

    const id = $(this).data("id");

    if (!confirm("¿Restaurar este vehículo?")) return;

    $.post("/modulos/vehiculos/acciones/restaurar.php", { id }, function (resp) {

        alert(resp.msg);

        if (resp.ok) {
            VehiculosDT.reloadActivos();
            VehiculosDT.reloadInactivos();
        }

    }, "json");
});


// ---------------------------------------------------------
// ELIMINAR DEFINITIVO (Hard Delete)
// ---------------------------------------------------------
$(document).on("click", ".btn-delete", function () {

    const id = $(this).data("id");

    if (!confirm("¿Eliminar DEFINITIVAMENTE este vehículo? Esta acción no se puede deshacer.")) return;

    $.post("/modulos/vehiculos/acciones/eliminar.php", { id }, function (resp) {

        alert(resp.msg);

        if (resp.ok) {
            VehiculosDT.reloadActivos();
            VehiculosDT.reloadInactivos();
        }

    }, "json");
});
