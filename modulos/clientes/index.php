<?php
// archivo: /modulos/clientes/index.php
// Página principal del módulo Clientes

require_once __DIR__ . '/../../includes/config.php';

// ============================
// AUTENTICACIÓN
// ============================
if (!isset($_SESSION['usuario'])) {
    header("Location: /login.php");
    exit;
}

// ============================
// CONEXIÓN GLOBAL
// ============================
$conn = getConnection();
if (!$conn) {
    die("❌ Error crítico: No se pudo conectar a la base de datos.");
}

// ============================
// VARIABLES DEL MÓDULO
// ============================
$titulo    = 'Módulo Clientes';
$subtitulo = 'Gestión de Clientes';
$icono     = 'fa-solid fa-users';

// ============================
// HEAD
// ============================
$head = __DIR__ . '/componentes/head.php';
if (file_exists($head)) include $head;
?>

<body class="bg-light">

<?php
// ============================
// HEADER GLOBAL
// ============================
$header_global = __DIR__ . '/../../includes/componentes/header_global.php';
if (file_exists($header_global)) include $header_global;
?>

<div class="container-fluid py-1">

    <?php
    // ============================
    // HEADER LOCAL
    // ============================
    $header_local = __DIR__ . '/componentes/header.php';
    if (file_exists($header_local)) include $header_local;

    // ============================
    // TABS
    // ============================
    $tabs = __DIR__ . '/componentes/tabs.php';
    if (file_exists($tabs)) include $tabs;
    ?>

</div>

<?php
// ============================
// MODALES DEL MÓDULO
// ============================
$modales = [
    '/modales/modal_ver_cliente.php',
    '/modales/modal_cliente.php',
    '/modales/modal_historial.php'
];

foreach ($modales as $modal) {
    $ruta = __DIR__ . $modal;
    if (file_exists($ruta)) include $ruta;
}
?>

<!-- ============================
     LIBRERÍAS BASE
     ============================ -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="/includes/js/global.js"></script>
<script src="/includes/js/validaciones_globales.js"></script>
<script src="/includes/js/modales_globales.js"></script>

<!-- ============================
     UBIGEO (DEBE IR ANTES DE form.js)
     ============================ -->
<script src="/modulos/ubigeo/assets/ubigeo.js"></script>

<!-- ============================
     SCRIPTS DEL MÓDULO CLIENTES
     ============================ -->
<script src="/modulos/clientes/assets/datatables.js"></script>
<script src="/modulos/clientes/assets/acciones.js"></script>
<script src="/modulos/clientes/assets/modal.js"></script>
<script src="/modulos/clientes/assets/form.js"></script>

<!-- ============================
     ORQUESTADOR (clientes.js)
     ============================ -->
<script src="/modulos/clientes/js/clientes.js"></script>

</body>
</html>
