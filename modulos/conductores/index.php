<?php
// archivo: /modulos/conductores/index.php
// Página principal del módulo Conductores
// Requiere autenticación

// Seguridad
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /login.php");
    exit;
}

$titulo    = 'Módulo Conductores';
$subtitulo = 'Gestión de Conductores';
$icono     = 'fa-solid fa-id-card-clip';

include __DIR__ . '/componentes/head.php';
?>

<body class="bg-light">

<?php include __DIR__ . '/../../includes/componentes/header_global.php'; ?>

<!-- FIX: container-fluid evita el ancho reducido -->
<div class="container-fluid py-1">
    <?php include __DIR__ . '/componentes/header.php'; ?>
    <?php include __DIR__ . '/componentes/tabs.php'; ?>
</div>

<?php include __DIR__ . '/modales/modal_ver_conductor.php'; ?>
<?php include __DIR__ . '/modales/modal_conductor.php'; ?>

<!-- Librerías base -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Ubigeo -->
<script src="/modulos/ubigeo/assets/ubigeo.js"></script>


<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Scripts globales del ERP -->
<script src="/includes/js/global.js"></script>
<script src="/includes/js/validaciones_globales.js"></script>
<script src="/includes/js/modales_globales.js"></script>

<!-- ============================================================
     ORDEN CORRECTO DE SCRIPTS DEL MÓDULO
     ============================================================ -->

<!-- 1. DataTables del módulo -->
<script src="/modulos/conductores/assets/datatables.js"></script>

<!-- 2. Acciones (usa DataTables) -->
<script src="/modulos/conductores/assets/acciones.js"></script>

<!-- 3. Modales -->
<script src="/modulos/conductores/assets/modal.js"></script>

<!-- 4. Formularios -->
<script src="/modulos/conductores/assets/form.js"></script>

<!-- 5. Orquestador (inicializa todo) -->
<script src="/modulos/conductores/js/conductores.js"></script>

<script>
console.log('🚚 index.php del módulo Conductores cargado correctamente');
</script>

</body>
</html>