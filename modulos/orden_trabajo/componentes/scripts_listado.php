<?php
// archivo: /modulos/orden_trabajo/componentes/scripts_listado.php
?>

<!-- jQuery 3.7.1 -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap 5.3 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables núcleo -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- DataTables con Bootstrap 5 -->
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {

    const config = {
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },
        order: [],
        pageLength: 25,
        responsive: true,
        autoWidth: false
    };

    if ($('#tablaOrdenesActivas').length) {
        $('#tablaOrdenesActivas').DataTable(config);
    }
    if ($('#tablaOrdenesAnuladas').length) {
        $('#tablaOrdenesAnuladas').DataTable(config);
    }
    if ($('#tablaOrdenesEliminadas').length) {
        $('#tablaOrdenesEliminadas').DataTable(config);
    }

});
</script>

<!-- JS del módulo OT (solo listado) -->
<script src="/modulos/orden_trabajo/js/ordenes.js"></script>
<script src="/modulos/orden_trabajo/js/filtros.js"></script>
<script src="/modulos/orden_trabajo/js/modales.js"></script>
<script src="/modulos/orden_trabajo/js/ajax.js"></script>
