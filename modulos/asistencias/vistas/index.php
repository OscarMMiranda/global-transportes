<?php
// archivo: /modulos/asistencias/vistas/index.php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

// CORE MODULAR
require_once __DIR__ . '/../core/asistencia.func.php';
require_once __DIR__ . '/../core/empresas.func.php';
require_once __DIR__ . '/../core/conductores.func.php';
require_once __DIR__ . '/../core/fechas.func.php';
require_once __DIR__ . '/../core/matriz.func.php';
require_once __DIR__ . '/../core/helpers.func.php';

// Obtener asistencias para la tabla
$asistencias = obtener_asistencias($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Módulo de Asistencias</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- CSS GLOBAL DEL MÓDULO -->
    <link rel="stylesheet" href="/modulos/asistencias/assets/css/layout.css">
    <link rel="stylesheet" href="/modulos/asistencias/assets/css/panel.css">
    <link rel="stylesheet" href="/modulos/asistencias/assets/css/asistencia.css">
</head>

<body>


    <div class="contenido">
        <h2>Módulo de Asistencias</h2>

        <!-- Tarjetas principales -->
        <?php include __DIR__ . '/../componentes/panel_tarjetas.php'; ?>

        <!-- Tabla de asistencias -->
        <?php include __DIR__ . '/../componentes/tabla_asistencias.php'; ?>
    </div>

    <!-- MODAL: Registrar Asistencia -->
    <?php include __DIR__ . '/../modales/modal_registrar_asistencia.php'; ?>

    <!-- MODAL: Modificar Asistencia -->
    <?php include __DIR__ . '/../modales/modal_modificar_asistencia.php'; ?>

    <?php include __DIR__ . '/../layout/footer.php'; ?>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SOLO scripts globales -->
    <script src="/modulos/asistencias/js/global.js"></script>

    <!-- Solo scripsts del sidebar -->
    <script src="/modulos/asistencias/js/sidebar.js"></script>

    <!-- Scripts del registrar asistencia -->
    <script src="/modulos/asistencias/js/registrar_asistencia.js"></script>



    <?php include __DIR__ . '/../layout/header.php'; ?>
    <?php include __DIR__ . '/../componentes/sidebar.php'; ?>

</body>
</html>
