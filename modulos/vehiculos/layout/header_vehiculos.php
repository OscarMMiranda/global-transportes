<?php
// archivo: modulos/vehiculos/layout/header_vehiculos.php

// 1) Iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2) Modo depuración (solo en desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// 3) Cargar configuración y funciones
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php';

// 4) Validar sesión y rol de administrador
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    header("Location: /login.php");
    exit();
}

// 5) Obtener conexión
$conn = getConnection();

// 6) Obtener nombre del usuario (compatible con string o array)
$nombre_usuario = '—';
if (isset($_SESSION['usuario'])) {
    if (is_array($_SESSION['usuario']) && isset($_SESSION['usuario']['nombre_completo'])) {
        $nombre_usuario = $_SESSION['usuario']['nombre_completo'];
    } elseif (is_string($_SESSION['usuario'])) {
        $nombre_usuario = $_SESSION['usuario'];
    }
}

// 7) Registrar trazabilidad
registrarEnHistorial($conn, $nombre_usuario, 'Accedió al módulo Vehículos', 'vehiculos', obtenerIP());

// 8) Otros datos globales
$titulo = isset($titulo) ? $titulo : 'Módulo Vehículos';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($titulo) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="controlador.php?action=list" title="Ir al listado de vehículos" aria-label="Listado de vehículos">
                <i class="fas fa-truck"></i> Vehículos
            </a>
            <div class="ms-auto d-flex align-items-center">
                <span class="navbar-text me-3">
                    Usuario: <?= htmlspecialchars($nombre_usuario) ?>
                </span>
                <a href="/logout.php" class="btn btn-outline-secondary btn-sm">Salir</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Mensajes flash -->
        <?php if (!empty($_SESSION['msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['msg']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>