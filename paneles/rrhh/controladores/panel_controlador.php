<?php
// archivo: /paneles/rrhh/controladores/panel_controlador.php

session_start();

// --------------------------------------------------------------
// 1. Validar sesión
// --------------------------------------------------------------
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
    header("Location: /login.php");
    exit;
}

// --------------------------------------------------------------
// 2. Validar que el rol sea RRHH
// --------------------------------------------------------------
if ($_SESSION['rol_nombre'] !== 'rrhh') {
    echo "Acceso denegado. Este panel es solo para Recursos Humanos.";
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
    registrarActividad($conn, $_SESSION['usuario'], 'Acceso al Panel RRHH');
}

// --------------------------------------------------------------
// 5. Definir tarjetas del panel RRHH
// --------------------------------------------------------------
$tarjetas = [
    [
        'titulo'      => 'Gestión de Personal',
        'icono'       => 'fa-users',
        'descripcion' => 'Altas, bajas y actualización de empleados.',
        'ruta'        => '/modulos/personal/'
    ],
    [
        'titulo'      => 'Asistencia',
        'icono'       => 'fa-calendar-check',
        'descripcion' => 'Control de asistencia y permisos.',
        'ruta'        => '/modulos/asistencia/'
    ],
    [
        'titulo'      => 'Contratos',
        'icono'       => 'fa-file-contract',
        'descripcion' => 'Gestión de contratos laborales.',
        'ruta'        => '/modulos/contratos/'
    ],
    [
        'titulo'      => 'Evaluaciones',
        'icono'       => 'fa-chart-line',
        'descripcion' => 'Evaluación de desempeño del personal.',
        'ruta'        => '/modulos/evaluaciones/'
    ],
    [
        'titulo'      => 'Reportes RRHH',
        'icono'       => 'fa-file-alt',
        'descripcion' => 'Indicadores y reportes del área.',
        'ruta'        => '/modulos/reportes/rrhh.php'
    ]
];

// --------------------------------------------------------------
// 6. Cargar vista
// --------------------------------------------------------------
require_once __DIR__ . '/../vistas/index.php';