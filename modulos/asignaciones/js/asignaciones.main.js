// archivo: /modulos/asignaciones/js/asignaciones.main.js
// Orquestador principal del módulo Asignaciones

$(document).ready(function () {

    console.log("Inicializando módulo Asignaciones...");

    // 1. Inicializar filtros (carga de combos)
    initFiltrosAsignaciones();

    // 2. Inicializar tabla principal
    window.tabla = initTablaAsignaciones();

    // 3. Inicializar eventos (botones y filtros)
    initEventosAsignaciones();

    // 4. Inicializar modales (detalle, editar, finalizar, reasignar)
    initModalesAsignaciones();

    console.log("Módulo Asignaciones listo.");
});
