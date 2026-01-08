<?php
	// archivo: /modulos/documentos/index.php

	session_start();

	//  Modo depuración (solo DEV)
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors',     1);
    ini_set('error_log',      __DIR__ . '/error_log.txt');

    // 3) Cargar config.php (define getConnection() y rutas)
    require_once __DIR__ . '/../../includes/config.php';

    // 4) Obtener la conexión
    $conn = getConnection();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Módulo de Documentos</title>
    <!-- DataTables + Bootstrap -->
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/datatables.min.css">
</head>
<body>
<div class="container mt-4">
    <h1 class="mb-4">📂 Gestión de Documentos</h1>

    <!-- Filtros -->
    <?php include __DIR__ . '/componentes/formularios/form_filtros.php'; ?>

    <!-- Tabla principal -->
    <div class="card">
        <div class="card-body">
            <?php include __DIR__ . '/componentes/tablas/tabla_documentos.php'; ?>
        </div>
    </div>

    <!-- Modales -->
    <?php include __DIR__ . '/componentes/modales/modal_ver.php'; ?>
    <?php include __DIR__ . '/componentes/modales/modal_subir.php'; ?>
</div>

<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/datatables.min.js"></script>
<script src="/modulos/documentos/js/documentos.js"></script>
</body>
</html>