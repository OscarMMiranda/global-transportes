<?php
//  archivo: /modulos/seguridad/permisos/index.php
//  Página principal del módulo de gestión de permisos  



require_once __DIR__ . '/../../../includes/config.php';
require_once INCLUDES_PATH . '/permisos.php';

// Permiso correcto del módulo de permisos
requirePermiso("permisos", "ver");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Permisos</title>

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- CSS interno del módulo -->
    <link rel="stylesheet" href="css/permisos.css">

    <!-- CSS del sidebar -->
    <link rel="stylesheet" href="css/sidebar.css">
</head>

<body class="bg-light p-3 with-sidebar">

<!-- SIDEBAR DEL MÓDULO PERMISOS -->
<?php include __DIR__ . '/sidebar.php'; ?>

<!-- COMPONENTES DEL MÓDULO -->
<?php include __DIR__ . '/componentes/topbar.php'; ?>
<?php include __DIR__ . '/componentes/breadcrumb.php'; ?>
<?php include __DIR__ . '/componentes/menu.php'; ?>
<?php include __DIR__ . '/componentes/info_panel.php'; ?>

<div class="panel d-flex gap-3">
    <?php include __DIR__ . '/componentes/panel_roles.php'; ?>
    <?php include __DIR__ . '/componentes/panel_modulos.php'; ?>
    <?php include __DIR__ . '/componentes/panel_acciones.php'; ?>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- JS interno del módulo -->
<script src="js/permisos.js"></script>

<!-- JS del sidebar -->
<script src="js/sidebar.js"></script>

</body>
</html>