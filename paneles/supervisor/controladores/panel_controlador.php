<?php
// archivo: /paneles/supervisor/controladores/panel_controlador.php

session_start();

// --------------------------------------------------------------
// 1. Validar sesión
// --------------------------------------------------------------
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
    header("Location: /login.php");
    exit;
}

// --------------------------------------------------------------
// 2. Validar que el rol sea SUPERVISOR
// --------------------------------------------------------------
if ($_SESSION['rol_nombre'] !== 'supervisor') {
    echo "Acceso denegado. Este panel es solo para supervisores.";
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
    registrarActividad($conn, $_SESSION['usuario'], 'Acceso al Panel Supervisor');
}

// --------------------------------------------------------------
// 5. Definir tarjetas del panel supervisor
// --------------------------------------------------------------
$tarjetas = [
    [
        'titulo'      => 'Supervisión de Tareas',
        'icono'       => 'fa-clipboard-check',
        'descripcion' => 'Revisar tareas asignadas a empleados.',
        'ruta'        => '/modulos/tareas/supervision.php'
    ],
    [
        'titulo'      => 'Control de Choferes',
        'icono'       => 'fa-truck-fast',
        'descripcion' => 'Monitorear rutas y desempeño.',
        'ruta'        => '/modulos/choferes/control.php'
    ],
    [
        'titulo'      => 'Reportes Operativos',
        'icono'       => 'fa-chart-bar',
        'descripcion' => 'Indicadores y métricas del área.',
        'ruta'        => '/modulos/reportes/operativos.php'
    ],
    [
        'titulo'      => 'Mi Perfil',
        'icono'       => 'fa-user',
        'descripcion' => 'Actualizar información personal.',
        'ruta'        => '/modulos/perfil/'
    ]
];

// --------------------------------------------------------------
// 6. Cargar vista
// --------------------------------------------------------------
require_once __DIR__ . '/../vistas/index.php';