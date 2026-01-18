<?php
// archivo: /paneles/comercial/controladores/panel_controlador.php

session_start();

// --------------------------------------------------------------
// 1. Validar sesión
// --------------------------------------------------------------
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
    header("Location: /login.php");
    exit;
}

// --------------------------------------------------------------
// 2. Validar que el rol sea COMERCIAL
// --------------------------------------------------------------
if ($_SESSION['rol_nombre'] !== 'comercial') {
    echo "Acceso denegado. Este panel es solo para el área comercial.";
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
    registrarActividad($conn, $_SESSION['usuario'], 'Acceso al Panel Comercial');
}

// --------------------------------------------------------------
// 5. Definir tarjetas del panel comercial
// --------------------------------------------------------------
$tarjetas = [
    [
        'titulo'      => 'Clientes',
        'icono'       => 'fa-user-tie',
        'descripcion' => 'Gestión de clientes y prospectos.',
        'ruta'        => '/modulos/clientes/'
    ],
    [
        'titulo'      => 'Cotizaciones',
        'icono'       => 'fa-file-signature',
        'descripcion' => 'Crear y administrar cotizaciones.',
        'ruta'        => '/modulos/cotizaciones/'
    ],
    [
        'titulo'      => 'Órdenes de Servicio',
        'icono'       => 'fa-file-invoice',
        'descripcion' => 'Generación y seguimiento de órdenes.',
        'ruta'        => '/modulos/ordenes/'
    ],
    [
        'titulo'      => 'Seguimiento Comercial',
        'icono'       => 'fa-chart-line',
        'descripcion' => 'Control de oportunidades y ventas.',
        'ruta'        => '/modulos/seguimiento/'
    ],
    [
        'titulo'      => 'Reportes Comerciales',
        'icono'       => 'fa-chart-bar',
        'descripcion' => 'Indicadores y métricas del área.',
        'ruta'        => '/modulos/reportes/comercial.php'
    ]
];

// --------------------------------------------------------------
// 6. Cargar vista
// --------------------------------------------------------------
require_once __DIR__ . '/../vistas/index.php';