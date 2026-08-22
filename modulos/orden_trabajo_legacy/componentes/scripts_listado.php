<?php
// archivo: /modulos/orden_trabajo/componentes/scripts_listado.php
?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables núcleo -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- DataTables con Bootstrap 5 -->
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
/**
 * Inicializa DataTables sobre cualquier tabla OT
 */
function inicializarTabla(selector) {

    if ($.fn.DataTable.isDataTable(selector)) {
        $(selector).DataTable().destroy();
    }

    $(selector).DataTable({
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },
        pageLength: 15,
        lengthMenu: [10, 15, 20, 25, 50, 100],
        responsive: true,
        autoWidth: false,
        order: []
    });
}

$(document).ready(function() {

    // Inicializar todas las tablas del módulo OT
    if ($("#tablaOrdenesActivas").length) {
        inicializarTabla("#tablaOrdenesActivas");
    }

    if ($("#tablaOrdenesAnuladas").length) {
        inicializarTabla("#tablaOrdenesAnuladas");
    }

    if ($("#tablaOrdenesEliminadas").length) {
        inicializarTabla("#tablaOrdenesEliminadas");
    }

});
</script>

<!-- JS del módulo OT -->
<script src="/modulos/orden_trabajo/js/ordenes.js?v=1.0"></script>
<script src="/modulos/orden_trabajo/js/filtros.js?v=1.0"></script>
<script src="/modulos/orden_trabajo/js/modales.js?v=1.0"></script>
<script src="/modulos/orden_trabajo/js/ajax.js?v=1.0"></script>

<!-- Catálogos (crítico para modal EDITAR) -->
<script src="/modulos/orden_trabajo/js/catalogos.js?v=1.0"></script>
