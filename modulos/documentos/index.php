<?php
// archivo: /modulos/documentos/index.php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

require_once __DIR__ . '/../../includes/config.php';
$conn = getConnection();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include __DIR__ . '/componentes/head.php'; ?>
</head>

<body>

    <?php include __DIR__ . '/componentes/header.php'; ?>
    <?php include __DIR__ . '/componentes/navbar.php'; ?>

    <div class="container mt-4">

        <?php include __DIR__ . '/componentes/formularios/form_filtros.php'; ?>

        <?php include __DIR__ . '/componentes/botones/boton_nuevo.php'; ?>

        <?php include __DIR__ . '/componentes/tablas/tabla_documentos.php'; ?>

        <?php include __DIR__ . '/componentes/modales/modal_ver.php'; ?>
        <?php include __DIR__ . '/componentes/modales/modal_subir.php'; ?>

    </div>

    <?php include __DIR__ . '/componentes/footer.php'; ?>
    <?php include __DIR__ . '/componentes/scripts.php'; ?>

</body>
</html>
