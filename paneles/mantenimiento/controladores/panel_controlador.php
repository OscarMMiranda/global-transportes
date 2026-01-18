<?php
// archivo: /paneles/mantenimiento/controladores/panel_controlador.php


session_start();

// --------------------------------------------------------------
// 1. Validar sesión
// --------------------------------------------------------------
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
    header("Location: /login.php");
    exit;
}

// --------------------------------------------------------------
// 2. Validar que el rol sea MANTENIMIENTO
// --------------------------------------------------------------
if ($_SESSION['rol_nombre'] !== 'mantenimiento') {
    echo "Acceso denegado. Este panel es solo para mantenimiento.";
    exit;
}

// --------------------------------------------------------------
// 3. Cargar configuración global
// --------------------------------------------------------------
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.php';

$conn = getConnection();

// --------------------------------------------------------------
// 4. Registrar acceso (si existe auditoría)
// --------------------------------------------------------------
if (function_exists('registrarActividad')) {
    registrarActividad($conn, $_SESSION['usuario'], 'Acceso al Panel Mantenimiento');
}

// --------------------------------------------------------------
// 5. Definir tarjetas del panel mantenimiento
// --------------------------------------------------------------
$tarjetas = [
    [
        'titulo'      => 'Unidades',
        'icono'       => 'fa-truck',
        'descripcion' => 'Listado de vehículos y estado técnico.',
        'ruta'        => '/modulos/unidades/'
    ],
    [
        'titulo'      => 'Mantenimiento Preventivo',
        'icono'       => 'fa-tools',
        'descripcion' => 'Programación y control de mantenimientos.',
        'ruta'        => '/modulos/mantenimiento/preventivo.php'
    ],
    [
        'titulo'      => 'Mantenimiento Correctivo',
        'icono'       => 'fa-wrench',
        'descripcion' => 'Registro de fallas y reparaciones.',
        'ruta'        => '/modulos/mantenimiento/correctivo.php'
    ],
    [
        'titulo'      => 'Repuestos',
        'icono'       => 'fa-cogs',
        'descripcion' => 'Control de repuestos y consumibles.',
        'ruta'        => '/modulos/repuestos/'
    ],
    [
        'titulo'      => 'Reportes Técnicos',
        'icono'       => 'fa-clipboard-check',
        'descripcion' => 'Historial, costos y métricas.',
        'ruta'        => '/modulos/reportes/mantenimiento.php'
    ]
];

// --------------------------------------------------------------
// 6. Cargar vista
// --------------------------------------------------------------
require_once __DIR__ . '/../vistas/index.php';