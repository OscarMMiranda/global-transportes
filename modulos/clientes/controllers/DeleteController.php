<?php
// archivo: modulos/clientes/controllers/DeleteController.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1) Cargar configuración y modelo
require_once rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/includes/config.php';
require_once rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/modulos/clientes/models/Cliente.php';
require_once rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/modulos/clientes/models/funciones.php'; // contiene registrarHistorialCliente()

// 2) Inicializar conexión y modelo
$conn = getConnection();
Cliente::init($conn);

// 3) Validar ID recibido por GET
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header('Location: index.php?action=list&msg=error');
    exit;
}

$id = (int) $_GET['id'];

// 4) Ejecutar borrado lógico
$ok = Cliente::delete($id);

// 5) Registrar historial si fue exitoso
if ($ok) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start(); // Asegura acceso a $_SESSION
    }

    // Capturar motivo desde POST si está disponible
    $motivo = isset($_POST['motivo']) ? trim($_POST['motivo']) : 'Desactivación manual';

    // Estados para trazabilidad
    $estado_anterior = 'Activo';
    $estado_nuevo    = 'Inactivo';

    // Registrar en historial
    registrarHistorialCliente(
        $conn,
        $id,
        'Desactivado',
        $estado_anterior,
        $estado_nuevo,
        $motivo
    );
}

// 6) Redirigir con feedback
$msg = $ok ? 'ok' : 'error';
header('Location: index.php?action=list&msg=' . $msg);
exit;