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

        var $select = $('[data-role="filtro-conductor"]');
        $select.empty().append('<option value="">Todos</option>');

        data.forEach(function(item) {
            $select.append('<option value="' + item.nombre + '">' + item.nombre + '</option>');
        });
    });
}



/**
 * Cargar tractos en el filtro
 */
function cargarFiltroTractos() {
    $.getJSON('api/recursos/tractos.php', function (data) {

        var $select = $('[data-role="filtro-tracto"]');
        $select.empty().append('<option value="">Todos</option>');

        data.forEach(function(item) {
            $select.append('<option value="' + item.placa + '">' + item.placa + '</option>');
        });
    });
}



/**
 * Cargar carretas en el filtro
 */
function cargarFiltroCarretas() {
    $.getJSON('api/recursos/carretas.php', function (data) {

        var $select = $('[data-role="filtro-carreta"]');
        $select.empty().append('<option value="">Todos</option>');

        data.forEach(function(item) {
            $select.append('<option value="' + item.placa + '">' + item.placa + '</option>');
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

        // Actualizar filtros dependientes
        if (typeof actualizarFiltrosDependientes === 'function') {
            actualizarFiltrosDependientes();
        }

        // Recargar tabla
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



/* ============================================================
   FILTROS DEPENDIENTES
   ============================================================ */

function actualizarFiltrosDependientes() {

    var filtros = obtenerParametrosFiltros();

    $.getJSON('api/filtros.php', filtros, function (data) {

        actualizarSelect('[data-role="filtro-conductor"]', data.conductores);
        actualizarSelect('[data-role="filtro-tracto"]', data.tractos);
        actualizarSelect('[data-role="filtro-carreta"]', data.carretas);
        actualizarSelect('[data-role="filtro-estado"]', data.estados);
    });
}

function actualizarSelect(selector, valores) {

    var $sel = $(selector);
    var valorActual = $sel.val();

    $sel.empty();
    $sel.append('<option value="">Todos</option>');

    valores.forEach(function(v) {
        $sel.append('<option value="' + v + '">' + v + '</option>');
    });

    if (valores.indexOf(valorActual) !== -1) {
        $sel.val(valorActual);
    }
}
