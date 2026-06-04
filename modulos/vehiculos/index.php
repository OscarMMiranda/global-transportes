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

$tituloPagina = "Gestión de Vehículos";

include __DIR__ . '/componentes/head.php';
?>

<body>

<?php include __DIR__ . '/../../includes/navbar.php'; ?>

<div class="container mt-4">

    <?php include __DIR__ . '/layout/header_vehiculos.php'; ?>

    <!-- Tabs + tablas -->
    <?php include __DIR__ . '/componentes/tabs.php'; ?>

    <!-- Modales -->
    <?php include __DIR__ . '/modales/modal_vehiculo.php'; ?>
    <?php include __DIR__ . '/modales/modal_ver_vehiculo.php'; ?>

    <?php include __DIR__ . '/modales/modal_agregar_foto.php'; ?>
    <?php include __DIR__ . '/modales/modal_editar_descripcion.php'; ?>
    <?php include __DIR__ . "/modales/modal_editar.php"; ?>
    <?php include __DIR__ . '/modales/modal_configuracion.php'; ?>

    <?php include __DIR__ . '/modales/modal_subir_documento.php'; ?>

    <?php include __DIR__ . "/modales/modal_editar_documento.php"; ?>


</div>

<!-- Modal Confirmación (fuera del container) -->
<?php include __DIR__ . '/modales/modal_confirmacion.php'; ?>

<?php include __DIR__ . '/componentes/footer.php'; ?>

</body>
</html>
