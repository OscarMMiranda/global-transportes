// archivo: /modulos/asignaciones/js/asignaciones.init.js
// Inicialización principal del módulo Asignaciones

$(function () {

    console.log("Inicializando módulo Asignaciones...");

    // ============================================================
    // 1. Inicializar filtros (carga de selects + eventos)
    // ============================================================
    if (typeof initFiltrosAsignaciones === 'function') {
        initFiltrosAsignaciones();
    } else {
        console.error("ERROR: initFiltrosAsignaciones() no está definido.");
    }

    // ============================================================
    // 2. Inicializar tabla principal
    // ============================================================
    if (typeof initTablaAsignaciones === 'function') {

        // Evitar re-inicialización accidental
        if ($.fn.DataTable.isDataTable('#tablaAsignaciones')) {
            console.warn("La tabla ya estaba inicializada. Destruyendo instancia previa...");
            $('#tablaAsignaciones').DataTable().clear().destroy();
        }

        window.tabla = initTablaAsignaciones();

    } else {
        console.error("ERROR: initTablaAsignaciones() no está definido.");
    }

    // ============================================================
    // 3. Recargar tabla y actualizar filtros dependientes
    // ============================================================
    $('[data-role^="filtro-"]').on('change', function () {

        // Actualizar los demás selects según los filtros actuales
        if (typeof actualizarFiltrosDependientes === 'function') {
            actualizarFiltrosDependientes();
        } else {
            console.error("ERROR: actualizarFiltrosDependientes() no está definido.");
        }

        // Recargar tabla
        if (window.tabla) {
            console.log("Filtro cambiado, recargando tabla...");
            window.tabla.ajax.reload();
        } else {
            console.error("ERROR: tabla no está inicializada.");
        }
    });

});
