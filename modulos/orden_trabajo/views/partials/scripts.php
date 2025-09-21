<?php
// archivo: /modulos/orden_trabajo/views/partials/scripts.php
?>

<!-- jQuery, Bootstrap y DataTables JS desde CDN -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<!-- Inicialización segura de DataTables -->
<script>
  $(document).ready(function() {
    const config = {
      language: {
        url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
      },
      order: []
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