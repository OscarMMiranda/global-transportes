<?php
// archivo: /paneles/atencion/controladores/panel_controlador.php

session_start();

// --------------------------------------------------------------
// 1. Validar sesión
// --------------------------------------------------------------
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
    header("Location: /login.php");
    exit;
}

// --------------------------------------------------------------
// 2. Validar que el rol sea ATENCIÓN AL CLIENTE
// --------------------------------------------------------------
if ($_SESSION['rol_nombre'] !== 'atencion') {
    echo "Acceso denegado. Este panel es solo para Atención al Cliente.";
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
    registrarActividad($conn, $_SESSION['usuario'], 'Acceso al Panel Atención al Cliente');
}

// --------------------------------------------------------------
// 5. Definir tarjetas del panel Atención al Cliente
// --------------------------------------------------------------
$tarjetas = [
    [
        'titulo'      => 'Tickets',
        'icono'       => 'fa-ticket',
        'descripcion' => 'Gestión de tickets y reclamos.',
        'ruta'        => '/modulos/tickets/'
    ],
    [
        'titulo'      => 'Casos Abiertos',
        'icono'       => 'fa-folder-open',
        'descripcion' => 'Seguimiento de casos activos.',
        'ruta'        => '/modulos/casos/abiertos.php'
    ],
    [
        'titulo'      => 'Historial del Cliente',
        'icono'       => 'fa-clock-rotate-left',
        'descripcion' => 'Revisión de interacciones previas.',
        'ruta'        => '/modulos/historial/'
    ],
    [
        'titulo'      => 'Mensajería',
        'icono'       => 'fa-comments',
        'descripcion' => 'Comunicación con clientes.',
        'ruta'        => '/modulos/mensajeria/'
    ],
    [
        'titulo'      => 'Reportes de Atención',
        'icono'       => 'fa-chart-bar',
        'descripcion' => 'Indicadores de satisfacción y tiempos.',
        'ruta'        => '/modulos/reportes/atencion.php'
    ]
];

// --------------------------------------------------------------
// 6. Cargar vista
// --------------------------------------------------------------
require_once __DIR__ . '/../vistas/index.php';