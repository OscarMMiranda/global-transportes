<?php
// archivo: /modulos/mantenimiento/tipo_vehiculo/index.php

// 1) Modo depuración (solo en desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// 2) Sesión y control de acceso
session_start();
if (empty($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    header('Location: ../../../login.php');
    exit;
}

// 3) Carga de dependencias globales
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/conexion.php';
require_once __DIR__ . '/modelo/TipoVehiculoModel.php';
require_once __DIR__ . '/controller.php';

// 4) Conexión única y validación defensiva
$conn = getConnection();
if (!($conn instanceof mysqli)) {
    die("❌ Error de conexión con la base de datos.");
}

// 5) Instancia del controlador
$ctrl = new TipoVehiculoController($conn);

// 6) Mini-ruteo por acción
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'index';

switch ($action) {
    case 'create':
        $ctrl->create(); // muestra formulario
        break;

    case 'store':
        $ctrl->store($_POST); // guarda nuevo registro
        break;

    case 'edit':
        $ctrl->edit((int) $_GET['id']); // muestra formulario de edición
        break;

    case 'update':
        $ctrl->update($_POST); // actualiza registro
        break;

    case 'delete':
        $ctrl->delete((int) $_GET['id']); // desactiva registro
        break;

    case 'reactivar_prompt':
        $ctrl->reactivar_prompt(); // muestra confirmación
        break;

    case 'reactivar':
        $ctrl->reactivar(); // reactiva registro
        break;

    default:
        // Vista principal con layout completo
        $layoutPath = realpath(__DIR__ . '/../componentes/layout/layout_base.php');
        if ($layoutPath && file_exists($layoutPath)) {
            require_once $layoutPath;
        } else {
            die("❌ No se encontró layout_base.php en: " . $layoutPath);
        }
        break;
}

// 7) Cerrar conexión
$conn->close();