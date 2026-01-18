<?php
// archivo: /paneles/gerencia/controladores/panel_controlador.php

session_start();

// --------------------------------------------------------------
// 1. Validar sesión
// --------------------------------------------------------------
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
    header("Location: /login.php");
    exit;
}

// --------------------------------------------------------------
// 2. Validar que el rol sea GERENCIA
// --------------------------------------------------------------
if ($_SESSION['rol_nombre'] !== 'gerencia') {
    echo "Acceso denegado. Este panel es solo para gerencia.";
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
    registrarActividad($conn, $_SESSION['usuario'], 'Acceso al Panel Gerencia');
}

// --------------------------------------------------------------
// 5. Definir tarjetas del panel gerencia
// --------------------------------------------------------------
$tarjetas = [
    [
        'titulo'      => 'Dashboard Ejecutivo',
        'icono'       => 'fa-chart-line',
        'descripcion' => 'Indicadores globales del negocio.',
        'ruta'        => '/modulos/dashboard/ejecutivo.php'
    ],
    [
        'titulo'      => 'Reportes Estratégicos',
        'icono'       => 'fa-chart-pie',
        'descripcion' => 'KPIs, métricas y análisis.',
        'ruta'        => '/modulos/reportes/estrategicos.php'
    ],
    [
        'titulo'      => 'Finanzas Consolidadas',
        'icono'       => 'fa-coins',
        'descripcion' => 'Resumen financiero de la empresa.',
        'ruta'        => '/modulos/finanzas/consolidadas.php'
    ],
    [
        'titulo'      => 'Control de Operaciones',
        'icono'       => 'fa-gears',
        'descripcion' => 'Estado general de flota y logística.',
        'ruta'        => '/modulos/operaciones/overview.php'
    ],
    [
        'titulo'      => 'Auditoría del Sistema',
        'icono'       => 'fa-clipboard-list',
        'descripcion' => 'Registro de actividad y seguridad.',
        'ruta'        => '/modulos/seguridad/auditoria/'
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