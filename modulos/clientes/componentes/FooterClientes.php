<?php
// /modulos/clientes/componentes/FooterClientes.php

if (!defined('GT_APP')) {
    define('GT_APP', true);
}
?>

<!-- ========================= -->
<!--  LIBRERÍAS DEL MÓDULO     -->
<!-- ========================= -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- ========================= -->
<!--  JS ESPECÍFICO DEL MÓDULO -->
<!-- ========================= -->

<script src="js/clientes.tabla.js"></script>
<script src="js/clientes.modal.js"></script>
<script src="js/clientes.filtros.js"></script>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function () {

    // Inicializar tabla
    if (typeof inicializarTablaClientes === 'function') {
        inicializarTablaClientes();
    }

    // Inicializar modal
    if (typeof inicializarModalCliente === 'function') {
        inicializarModalCliente();
    }

    // Inicializar filtros
    if (typeof inicializarFiltrosClientes === 'function') {
        inicializarFiltrosClientes();
    }
});
</script>

</body>
</html>
