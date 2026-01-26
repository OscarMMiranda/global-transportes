// archivo: /modulos/conductores/js/conductores.js
// ORQUESTADOR DEL MÓDULO

$(document).ready(function () {

    console.log("🚚 conductores.js cargado correctamente");

    // Inicializar DataTables
    Conductores.initTablaActivos();
    Conductores.initTablaInactivos();

    // FIX: Ajustar columnas al cambiar de tab
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {

        const target = $(e.target).attr('data-bs-target');

        if (target === '#panel-inactivos' && Conductores.tablaInactivos) {
            Conductores.tablaInactivos.columns.adjust().draw(false);
        }

        if (target === '#panel-activos' && Conductores.tablaActivos) {
            Conductores.tablaActivos.columns.adjust().draw(false);
        }
    });

});