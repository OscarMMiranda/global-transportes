// archivo: /modulos/vehiculos/js/index.js

console.log("✅ index.js de Vehículos inicializado");

// NUEVO VEHÍCULO
$(document).on("click", ".btn-nuevo", function () {

    $("#modalVehiculoTitulo").text("Nuevo vehículo");

    $("#modalVehiculoBody").html(`
        <div class="text-center py-5">
            <div class="spinner-border text-primary"></div>
            <p class="mt-3">Cargando formulario...</p>
        </div>
    `);

    $("#modalVehiculoBody").load("/modulos/vehiculos/vistas/formulario.php");
});

// EDITAR VEHÍCULO
$(document).on("click", ".btn-editar", function () {

    const id = $(this).data("id");

    $("#modalVehiculoTitulo").text("Editar vehículo");

    $("#modalVehiculoBody").html(`
        <div class="text-center py-5">
            <div class="spinner-border text-primary"></div>
            <p class="mt-3">Cargando datos...</p>
        </div>
    `);

    $("#modalVehiculoBody").load("/modulos/vehiculos/vistas/formulario.php?id=" + id);
});

