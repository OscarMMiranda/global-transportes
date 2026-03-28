// archivo: /modulos/asignaciones/js/asignaciones.events.js
// Eventos de la tabla y acciones del módulo Asignaciones

function initEventosAsignaciones() {

    /* ============================================================
       FILTROS → recargar DataTable
       ============================================================ */
    $(document).on('change', '[data-role^="filtro-"]', function () {
        if (window.tabla) {
            window.tabla.ajax.reload();
        }
    });


    /* ============================================================
       BOTÓN FINALIZAR
       ============================================================ */
    $(document).on('click', '.btn-finalizar', function () {
        const id = $(this).data('id');
        $('#finalizar_id').val(id);
        $('#modalFinalizar').modal('show');
    });


    /* ============================================================
       BOTÓN EDITAR
       ============================================================ */
    $(document).on('click', '.btn-editar', function () {
        const id = $(this).data('id');
        abrirModalEditar(id);
    });


    /* ============================================================
       BOTÓN DETALLE
       ============================================================ */
    $(document).on('click', '.btn-detalle', function () {
        const id = $(this).data('id');
        abrirModalDetalle(id);
    });


    /* ============================================================
       BOTÓN REASIGNAR
       ============================================================ */
    $(document).on('click', '.btn-reasignar', function () {
        const id = $(this).data('id');
        abrirModalReasignar(id);
    });


    /* ============================================================
       BOTÓN HISTORIAL
       ============================================================ */
    $(document).on('click', '.btn-historial', function () {
        const id = $(this).data('id');
        abrirModalHistorial(id);
    });
}
