// archivo: /modulos/vehiculos/js/tabs/tab_config.js

function cargarTabConfig() {

    if (TAB_CARGADO.config) return;

    $("#tab-config").html(loaderHTML("Cargando configuración operativa..."));

    $.get("/modulos/vehiculos/acciones/editar_configuracion.php", { id: ID_VEHICULO_EDITAR })
        .done(function (resp) {
            $("#tab-config").html(resp);
            TAB_CARGADO.config = true;
        })
        .fail(function () {
            $("#tab-config").html(errorHTML("Error al cargar configuración operativa"));
        });
}
