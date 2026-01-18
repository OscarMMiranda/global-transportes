<?php
// archivo: /modulos/mantenimiento/tipo_vehiculo/index.php

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/permisos.php';
require_once __DIR__ . '/../../../includes/funciones.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

requirePermiso('tipo_vehiculo', 'ver');

$conn = getConnection();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tipo de Vehículo</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- Estilos del módulo -->
    <link rel="stylesheet" href="/modulos/mantenimiento/tipo_vehiculo/css/tipo_vehiculo.css">
</head>

<body>

<?php include __DIR__ . '/../../../includes/navbar.php'; ?>

<div class="container mt-4">

    <h3 class="mb-4">
        <i class="fa-solid fa-tags me-2"></i>
        Tipo de Vehículo
    </h3>

    <!-- Tabs + tablas -->
    <?php include __DIR__ . '/componentes/tabs.php'; ?>

    <!-- Modales -->
    <?php include __DIR__ . '/modales/modal_ver.php'; ?>
    <?php include __DIR__ . '/modales/modal_crear.php'; ?>
    <?php include __DIR__ . '/modales/modal_editar.php'; ?>

</div>

<!-- Modal de confirmación genérico -->
<?php include __DIR__ . '/modales/modal_confirmacion.php'; ?>

<!-- JS Core -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- JS del módulo -->
<script src="/modulos/mantenimiento/tipo_vehiculo/js/notificaciones.js"></script>
<script src="/modulos/mantenimiento/tipo_vehiculo/js/datatables.js"></script>
<script src="/modulos/mantenimiento/tipo_vehiculo/js/confirmacion.js"></script>
<script src="/modulos/mantenimiento/tipo_vehiculo/js/acciones.js"></script>
<script src="/modulos/mantenimiento/tipo_vehiculo/js/form.js"></script>
<script src="/modulos/mantenimiento/tipo_vehiculo/js/modal.js"></script>
<script src="/modulos/mantenimiento/tipo_vehiculo/js/editar.js"></script>

</body>
</html>