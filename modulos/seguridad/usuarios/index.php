<?php
    // archivo: /modulos/seguridad/usuarios/index.php
    // Página principal del módulo de gestión de usuarios

    require_once __DIR__ . '/../../../includes/config.php';
    require_once INCLUDES_PATH . '/permisos.php';

    // Permiso correcto del módulo "usuarios"
    requirePermiso("usuarios", "ver");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- CSS interno del módulo -->
    <link rel="stylesheet" href="css/usuarios.css">

    <!-- CSS de notificaciones ERP -->
    <link rel="stylesheet" href="css/notificaciones.css">

    <!-- CSS del sidebar -->
    <link rel="stylesheet" href="../permisos/css/sidebar.css">
</head>

<body class="bg-light p-3 with-sidebar">

<!-- SIDEBAR DEL MÓDULO SEGURIDAD -->
<?php include __DIR__ . '/../permisos/sidebar.php'; ?>

<!-- COMPONENTES DEL MÓDULO -->
<?php include __DIR__ . '/componentes/topbar.php'; ?>
<?php include __DIR__ . '/componentes/breadcrumb.php'; ?>
<?php include __DIR__ . '/componentes/menu.php'; ?>

<div class="container-fluid mt-3">

    <div class="row">

        <!-- PANEL LISTA DE USUARIOS -->
        <div class="col-md-4">
            <?php include __DIR__ . '/componentes/panel_lista_usuarios.php'; ?>
        </div>

        <!-- PANEL FORMULARIO DE USUARIO -->
        <div class="col-md-8">
            <?php include __DIR__ . '/componentes/panel_form_usuario.php'; ?>
        </div>

    </div>

</div>

<!-- COMPONENTES ERP (DEBEN IR ANTES DE LOS JS) -->
<?php include __DIR__ . '/componentes/notificaciones.php'; ?>
<?php include __DIR__ . '/componentes/modal_confirmacion.php'; ?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- JS de notificaciones ERP -->
<script src="js/notificaciones.js"></script>

<!-- JS del modal de confirmación -->
<script src="js/modal_confirmacion.js"></script>

<!-- JS interno del módulo -->
<script src="js/usuarios.js"></script>

<!-- JS del sidebar -->
<script src="../permisos/js/sidebar.js"></script>

</body>
</html>
