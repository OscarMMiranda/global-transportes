<?php
// archivo: /modulos/seguridad/permisos/dashboard.php

require_once __DIR__ . '/../../../includes/config.php';
require_once INCLUDES_PATH . '/permisos.php';

requirePermiso("usuarios", "ver");

$conn = getConnection();

$roles = $conn->query("SELECT COUNT(*) AS total FROM roles")->fetch_assoc()['total'];
$modulos = $conn->query("SELECT COUNT(*) AS total FROM modulos")->fetch_assoc()['total'];
$acciones = $conn->query("SELECT COUNT(*) AS total FROM acciones")->fetch_assoc()['total'];
$permisos = $conn->query("SELECT COUNT(*) AS total FROM permisos_roles")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard de Seguridad</title>
    <link rel="stylesheet" href="css/permisos.css">

    <!-- Bootstrap + FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="p-4 bg-light">

<h2 class="mb-4"><i class="fa-solid fa-shield-halved"></i> Dashboard de Seguridad</h2>

<?php include __DIR__ . '/componentes/menu.php'; ?>

<div class="row g-3">

    <div class="col-md-3">
        <div class="card p-3 shadow-sm text-center">
            <i class="fa-solid fa-users fa-2x mb-2 text-primary"></i>
            <h4><?= $roles ?></h4>
            <p>Roles registrados</p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 shadow-sm text-center">
            <i class="fa-solid fa-layer-group fa-2x mb-2 text-success"></i>
            <h4><?= $modulos ?></h4>
            <p>Módulos del sistema</p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 shadow-sm text-center">
            <i class="fa-solid fa-bolt fa-2x mb-2 text-warning"></i>
            <h4><?= $acciones ?></h4>
            <p>Acciones disponibles</p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 shadow-sm text-center">
            <i class="fa-solid fa-key fa-2x mb-2 text-danger"></i>
            <h4><?= $permisos ?></h4>
            <p>Permisos asignados</p>
        </div>
    </div>

</div>

</body>
</html>