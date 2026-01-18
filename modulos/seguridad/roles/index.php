<?php
    // archivo: /modulos/seguridad/roles/index.php
    // Página principal del módulo de gestión de roles     

    require_once __DIR__ . '/../../../includes/config.php';
    require_once INCLUDES_PATH . '/permisos.php';

    // Permiso correcto del módulo "roles"
    requirePermiso("roles", "ver");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Roles</title>

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- CSS interno del módulo -->
    <link rel="stylesheet" href="css/roles.css">

    <!-- CSS de notificaciones ERP -->
    <link rel="stylesheet" href="css/notificaciones.css">

    <!-- CSS del sidebar (si lo usas en seguridad) -->
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

        <!-- PANEL LISTA DE ROLES -->
        <div class="col-md-4">
            <?php include __DIR__ . '/componentes/panel_lista_roles.php'; ?>
        </div>

        <!-- PANEL FORMULARIO DE ROL -->
        <div class="col-md-8">
            <?php include __DIR__ . '/componentes/panel_form_rol.php'; ?>
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

<!-- JS interno del módulo (DEBE IR AL FINAL) -->
<script src="js/roles.js"></script>

<!-- JS del sidebar -->
<script src="../permisos/js/sidebar.js"></script>

</body>
</html>