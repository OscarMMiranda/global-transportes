<?php
/**
 * Componente: FooterClientes.php
 * Ubicación: /modulos/clientes/componentes/
 * Propósito: Scripts específicos del módulo CLIENTES (versión segura para PHP 5.6)
 */

// Seguridad: evitar acceso directo
if (!defined('GT_APP')) {
    // En PHP 5.6 no se puede definir dentro del if si ya existe, así que solo definimos si no existe
    define('GT_APP', true);
}
?>

<!-- ========================= -->
<!--  JS ESPECÍFICO DEL MÓDULO -->
<!-- ========================= -->

<script type="text/javascript">
// Inicialización segura compatible con navegadores antiguos
document.addEventListener('DOMContentLoaded', function () {

    // Inicializar tabla si existe
    var tabla = document.getElementById('tablaClientes');
    if (tabla) {
        if (typeof inicializarTablaClientes === 'function') {
            inicializarTablaClientes();
        } else {
            console.log('inicializarTablaClientes() no está definido.');
        }
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

<!-- ========================= -->
<!--  ARCHIVOS JS DEL MÓDULO   -->
<!-- ========================= -->

<script type="text/javascript" src="/modulos/clientes/js/clientes.tabla.js"></script>
<script type="text/javascript" src="/modulos/clientes/js/clientes.modal.js"></script>
<script type="text/javascript" src="/modulos/clientes/js/clientes.filtros.js"></script>
