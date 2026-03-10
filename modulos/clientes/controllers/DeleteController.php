<?php
// archivo: /modulos/clientes/controllers/DeleteController.php

if (!defined('GT_APP')) {
    define('GT_APP', true);
}

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/funciones.php'; // registrarHistorialCliente()

$conn = getConnection();
Cliente::init($conn);

// 1) Validar ID
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header('Location: index.php?action=list&msg=error');
    exit;
}

$id = (int) $_GET['id'];

// 2) Obtener estado actual
$cliente = Cliente::find($id);

if (!$cliente) {
    header('Location: index.php?action=list&msg=error');
    exit;
}

$estado_anterior = $cliente['estado'];

if ($estado_anterior === 'Inactivo') {
    // Ya está inactivo → no registrar historial duplicado
    header('Location: index.php?action=list&msg=ok');
    exit;
}

// 3) Ejecutar borrado lógico
$ok = Cliente::delete($id);

// 4) Registrar historial
if ($ok) {

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $motivo = 'Desactivación manual';

    registrarHistorialCliente(
        $conn,
        $id,
        'Desactivado',
        $estado_anterior,
        'Inactivo',
        $motivo
    );
}

// 5) Redirigir
header('Location: index.php?action=list&msg=' . ($ok ? 'ok' : 'error'));
exit;
