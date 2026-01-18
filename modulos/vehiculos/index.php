<?php
// archivo: /modulos/vehiculos/index.php
require_once __DIR__ . '/../../includes/config.php';
require_once INCLUDES_PATH . '/permisos.php';
require_once INCLUDES_PATH . '/funciones.php';

$conn = getConnection();
requirePermiso('usuarios', 'ver');

registrarEnHistorial(
    $conn,
    $_SESSION['usuario'],
    "Visualizó lista de usuarios",
    "usuarios",
    $_SERVER['REMOTE_ADDR']
);

// Título del módulo
$tituloPagina = "Gestión de Vehículos";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $tituloPagina ?></title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- CSS propio -->
    <link rel="stylesheet" href="/modulos/vehiculos/css/vehiculos.css">
</head>

<body>

    <?php include __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="container mt-4">

        <h3 class="mb-4">
            <i class="fa-solid fa-car-side me-2"></i>
            Gestión de Vehículos
        </h3>

        <!-- Tabs + tablas -->
        <?php include __DIR__ . '/componentes/tabs.php'; ?>

        <!-- Modal Crear/Editar -->
        <?php include __DIR__ . '/modales/modal_vehiculo.php'; ?>

        <!-- Modal Ver -->
        <?php include __DIR__ . '/modales/modal_ver_vehiculo.php'; ?>

        <!-- Modal Configuración -->
        <?php include __DIR__ . '/modales/modal_configuracion.php'; ?>

    </div> <!-- cierre del container -->

    <!-- Modal Confirmación (DEBE IR FUERA DEL CONTAINER) -->
    <?php include __DIR__ . '/modales/modal_confirmacion.php'; ?>

    <!-- JS global -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- JS del módulo -->
    <script src="/modulos/vehiculos/js/datatables.js"></script>

    <!-- Confirmación debe cargarse ANTES de acciones.js -->
    <script src="/modulos/vehiculos/js/confirmacion.js"></script>
	<script src="/modulos/vehiculos/js/notificaciones.js"></script>
	
    <script src="/modulos/vehiculos/js/acciones.js"></script>
    <script src="/modulos/vehiculos/js/form.js"></script>
    <script src="/modulos/vehiculos/js/modal.js"></script>

    <script>
        console.log("✅ index.php cargado y scripts inicializados");
    </script>

</body>
</html>