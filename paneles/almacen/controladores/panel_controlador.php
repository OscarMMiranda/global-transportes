<?php
// archivo: /paneles/logistica/controladores/panel_controlador.php


session_start();

// --------------------------------------------------------------
// 1. Validar sesión
// --------------------------------------------------------------
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
    header("Location: /login.php");
    exit;
}

// --------------------------------------------------------------
// 2. Validar que el rol sea LOGÍSTICA
// --------------------------------------------------------------
if ($_SESSION['rol_nombre'] !== 'logistica') {
    echo "Acceso denegado. Este panel es solo para logística.";
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
    registrarActividad($conn, $_SESSION['usuario'], 'Acceso al Panel Logística');
}

// --------------------------------------------------------------
// 5. Definir tarjetas del panel logística
// --------------------------------------------------------------
$tarjetas = [
    [
        'titulo'      => 'Inventario',
        'icono'       => 'fa-boxes',
        'descripcion' => 'Control de stock, entradas y salidas.',
        'ruta'        => '/modulos/inventario/'
    ],
    [
        'titulo'      => 'Gestión de Cargas',
        'icono'       => 'fa-dolly',
        'descripcion' => 'Administración de cargas y paquetes.',
        'ruta'        => '/modulos/cargas/'
    ],
    [
        'titulo'      => 'Despachos',
        'icono'       => 'fa-truck-loading',
        'descripcion' => 'Programación y control de despachos.',
        'ruta'        => '/modulos/despachos/'
    ],
    [
        'titulo'      => 'Seguimiento',
        'icono'       => 'fa-map-location-dot',
        'descripcion' => 'Ubicación y estado de unidades.',
        'ruta'        => '/modulos/seguimiento/'
    ],
    [
        'titulo'      => 'Reportes Logísticos',
        'icono'       => 'fa-chart-area',
        'descripcion' => 'Indicadores y métricas del área.',
        'ruta'        => '/modulos/reportes/logistica.php'
    ]
];

// --------------------------------------------------------------
// 6. Cargar vista
// --------------------------------------------------------------
require_once __DIR__ . '/../vistas/index.php';