<?php
// archivo: /modulos/infracciones/index.php

// Sesión
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /login.php");
    exit;
}

// Debug temporal
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración global del ERP
require_once __DIR__ . '/../../includes/config.php';

// Controlador del módulo
require_once __DIR__ . '/controllers/InfraccionesController.php';
$controller = new InfraccionesController($GLOBALS['db']);

// Datos del módulo
$lista     = $controller->listar($_GET);
$entidades = $controller->entidades();

// Variables del módulo
$titulo    = 'Módulo de Infracciones';
$subtitulo = 'Gestión de Infracciones';
$icono     = 'fa-solid fa-triangle-exclamation';

// Head
include __DIR__ . '/componentes/head.php';
?>

<body class="bg-light">

<?php include __DIR__ . '/../../includes/componentes/header_global.php'; ?>

<div class="container-fluid py-1">

    <?php include __DIR__ . '/componentes/header.php'; ?>
    <?php include __DIR__ . '/componentes/tabs.php'; ?>

    <?php include __DIR__ . '/componentes/tabla.php'; ?>

</div>

<?php include __DIR__ . '/modales/modal_crear.php'; ?>
<?php include __DIR__ . '/modales/modal_editar.php'; ?>
<?php include __DIR__ . '/modales/modal_eliminar.php'; ?>

<?php include __DIR__ . '/componentes/scripts.php'; ?>

</body>
</html>
