<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../../../includes/config.php';
require_once '../../../includes/permisos.php';
requirePermiso("auditoria", "ver");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Auditoría del Sistema</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>

<body>

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <span class="navbar-brand">Panel de Auditoría</span>
        <a href="/modulos/seguridad/" class="btn btn-outline-light btn-sm">← Volver</a>
    </div>
</nav>

<div class="container-fluid">

    <h3 class="mb-3">Auditoría del Sistema</h3>

    <div class="card mb-4">
        <div class="card-body">
            <?php include __DIR__ . '/componentes/filtros.php'; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <?php include __DIR__ . '/componentes/tabla.php'; ?>
        </div>
    </div>

</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- JS del módulo -->
<script src="js/auditoria.js"></script>

</body>
</html>