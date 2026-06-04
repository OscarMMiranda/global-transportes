// archivo: /modulos/vehiculos/js/tabs/tab_datos.js
// ============================================================
// 
//  JS MAESTRO PARA EDITAR VEHÍCULO
// ============================================================

function cargarTabDatos() {

    if (TAB_CARGADO.datos) return;

    $("#tab-datos").html(loaderHTML("Cargando datos técnicos..."));

    $.get("/modulos/vehiculos/acciones/editar_datos.php", { id: ID_VEHICULO_EDITAR })
        .done(function (resp) {
            $("#tab-datos").html(resp);
            TAB_CARGADO.datos = true;
        })
        .fail(function () {
            $("#tab-datos").html(errorHTML("Error al cargar datos técnicos"));
        });
}
