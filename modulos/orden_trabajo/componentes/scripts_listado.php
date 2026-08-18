<?php
// archivo: /modulos/orden_trabajo/componentes/scripts_listado.php
?>

<!-- jQuery (única carga) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap (única carga) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables núcleo -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- DataTables con Bootstrap 5 -->
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
/**
 * Inicializa DataTables sobre la tabla OT
 * Se usa tanto en carga inicial como después del AJAX
 */
function inicializarTablaOT() {

    // Si ya existe una instancia previa → destruirla
    if ($.fn.DataTable.isDataTable("#tablaOT")) {
        $("#tablaOT").DataTable().destroy();
    }

    // Inicializar DataTables
    $("#tablaOT").DataTable({
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },
        pageLength: 25,
        lengthMenu: [10, 25, 50, 100],
        responsive: true,
        autoWidth: false,
        order: []
    });
}

$(document).ready(function() {

    // Inicialización en carga inicial
    if ($("#tablaOT").length) {
        inicializarTablaOT();
    }

});
</script>

<!-- JS del módulo OT -->
<script src="/modulos/orden_trabajo/js/ordenes.js?v=1.0"></script>
<script src="/modulos/orden_trabajo/js/filtros.js?v=1.0"></script>
<script src="/modulos/orden_trabajo/js/modales.js?v=1.0"></script>
<script src="/modulos/orden_trabajo/js/ajax.js?v=1.0"></script>

<!-- ⭐ Cargar catálogos AL FINAL (crítico para que el modal EDITAR funcione) -->
<script src="/modulos/orden_trabajo/js/catalogos.js?v=1.0"></script>
