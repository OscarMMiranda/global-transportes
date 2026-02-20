//  archivo  : /modulos/asistencias/reporte_mensual/js/rm_main/rm_init.js
// Funciones de inicialización del módulo de reporte mensual de asistencias

function rm_inicializar_modulo() {

    rm_log('Inicializando módulo...');

    // Cargar datos iniciales si aplica
    // rm_filtros_buscar();

    // Desbloquear pantalla
    rm_desbloquear_pantalla();
}

$(document).ready(function () {
    rm_inicializar_modulo();
});
