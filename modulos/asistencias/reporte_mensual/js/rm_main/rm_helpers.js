//  archivo  : /modulos/asistencias/reporte_mensual/js/rm_main/rm_helpers.js
// Funciones auxiliares para el módulo de reporte mensual de asistencias

function rm_log(mensaje) {
    console.log('[RM] ' + mensaje);
}

function rm_bloquear_pantalla() {
    $('#btn_buscar').prop('disabled', true);
}

function rm_desbloquear_pantalla() {
    $('#btn_buscar').prop('disabled', false);
}
