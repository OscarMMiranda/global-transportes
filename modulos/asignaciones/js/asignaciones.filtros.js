// archivo: /modulos/asignaciones/js/asignaciones.filtros.js

/**
 * Inicializa todos los filtros del módulo Asignaciones
 */
function initFiltrosAsignaciones() {
    cargarFiltroConductores();
    cargarFiltroTractos();
    cargarFiltroCarretas();
    initFiltroEstado();
    bindEventosFiltros();
}



/* ============================================================
   CARGA DE DATOS
   ============================================================ */

/**
 * Cargar conductores en el filtro
 */
function cargarFiltroConductores() {
    $.getJSON('api/recursos/conductores.php', function (data) {

        const $select = $('[data-role="filtro-conductor"]');
        $select.empty().append('<option value="">Todos</option>');

        data.forEach(item => {
            $select.append(`<option value="${item.id}">${item.nombre}</option>`);
        });
    });
}



/**
 * Cargar tractos en el filtro
 */
function cargarFiltroTractos() {
    $.getJSON('api/recursos/tractos.php', function (data) {

        const $select = $('[data-role="filtro-tracto"]');
        $select.empty().append('<option value="">Todos</option>');

        data.forEach(item => {
            $select.append(`<option value="${item.id}">${item.placa}</option>`);
        });
    });
}



/**
 * Cargar carretas en el filtro
 */
function cargarFiltroCarretas() {
    $.getJSON('api/recursos/carretas.php', function (data) {

        const $select = $('[data-role="filtro-carreta"]');
        $select.empty().append('<option value="">Todos</option>');

        data.forEach(item => {
            $select.append(`<option value="${item.id}">${item.placa}</option>`);
        });
    });
}



/**
 * Estado (no requiere API)
 */
function initFiltroEstado() {
    // Ya está en el HTML, pero lo dejamos modular por si luego se vuelve dinámico
}



/* ============================================================
   EVENTOS
   ============================================================ */

/**
 * Cuando cambia cualquier filtro → recargar DataTable
 */
function bindEventosFiltros() {

    $('[data-role^="filtro-"]').on('change', function () {
        if (window.tabla) {
            window.tabla.ajax.reload();
        }
    });
}



/* ============================================================
   OBTENER PARÁMETROS PARA DATATABLES
   ============================================================ */

/**
 * Devuelve los filtros actuales para enviarlos al servidor
 */
function obtenerParametrosFiltros() {
    return {
        conductor: $('[data-role="filtro-conductor"]').val(),
        tracto: $('[data-role="filtro-tracto"]').val(),
        carreta: $('[data-role="filtro-carreta"]').val(),
        estado: $('[data-role="filtro-estado"]').val()
    };
}
