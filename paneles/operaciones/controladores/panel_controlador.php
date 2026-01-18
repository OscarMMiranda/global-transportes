<?php
// archivo: /paneles/operaciones/controladores/panel_controlador.php

session_start();

// --------------------------------------------------------------
// 1. Validar sesión
// --------------------------------------------------------------
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
    header("Location: /login.php");
    exit;
}

// --------------------------------------------------------------
// 2. Validar que el rol sea OPERACIONES
// --------------------------------------------------------------
if ($_SESSION['rol_nombre'] !== 'operaciones') {
    echo "Acceso denegado. Este panel es solo para el área de operaciones.";
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
    registrarActividad($conn, $_SESSION['usuario'], 'Acceso al Panel Operaciones');
}

// --------------------------------------------------------------
// 5. Definir tarjetas del panel operaciones
// --------------------------------------------------------------
$tarjetas = [
    [
        'titulo'      => 'Control de Flota',
        'icono'       => 'fa-truck-moving',
        'descripcion' => 'Monitoreo de vehículos, estados y disponibilidad.',
        'ruta'        => '/modulos/flota/control_flota.php'
    ],
    [
        'titulo'      => 'Asignación de Rutas',
        'icono'       => 'fa-route',
        'descripcion' => 'Asignar rutas a choferes y vehículos.',
        'ruta'        => '/modulos/rutas/asignacion.php'
    ],
    [
        'titulo'      => 'Control de Cargas',
        'icono'       => 'fa-boxes-stacked',
        'descripcion' => 'Gestión de cargas, guías y manifiestos.',
        'ruta'        => '/modulos/cargas/control.php'
    ],
    [
        'titulo'      => 'Incidencias',
        'icono'       => 'fa-triangle-exclamation',
        'descripcion' => 'Registrar y gestionar incidencias operativas.',
        'ruta'        => '/modulos/incidencias/'
    ],
    [
        'titulo'      => 'Reportes Operativos',
        'icono'       => 'fa-chart-line',
        'descripcion' => 'Indicadores de desempeño y KPIs.',
        'ruta'        => '/modulos/reportes/operaciones.php'
    ]
];

// --------------------------------------------------------------
// 6. Cargar vista
// --------------------------------------------------------------
require_once __DIR__ . '/../vistas/index.php';