<?php
// archivo: /paneles/auditoria/controladores/panel_controlador.php

session_start();

// --------------------------------------------------------------
// 1. Validar sesión
// --------------------------------------------------------------
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
    header("Location: /login.php");
    exit;
}

// --------------------------------------------------------------
// 2. Validar que el rol sea AUDITORÍA
// --------------------------------------------------------------
if ($_SESSION['rol_nombre'] !== 'auditoria') {
    echo "Acceso denegado. Este panel es solo para Auditoría.";
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
    registrarActividad($conn, $_SESSION['usuario'], 'Acceso al Panel Auditoría');
}

// --------------------------------------------------------------
// 5. Definir tarjetas del panel Auditoría
// --------------------------------------------------------------
$tarjetas = [
    [
        'titulo'      => 'Auditoría de Usuarios',
        'icono'       => 'fa-user-check',
        'descripcion' => 'Revisión de acciones por usuario.',
        'ruta'        => '/modulos/auditoria/usuarios.php'
    ],
    [
        'titulo'      => 'Auditoría de Módulos',
        'icono'       => 'fa-layer-group',
        'descripcion' => 'Trazabilidad por módulo y operación.',
        'ruta'        => '/modulos/auditoria/modulos.php'
    ],
    [
        'titulo'      => 'Auditoría de Documentos',
        'icono'       => 'fa-file-alt',
        'descripcion' => 'Cambios en comprobantes, guías y contratos.',
        'ruta'        => '/modulos/auditoria/documentos.php'
    ],
    [
        'titulo'      => 'Bitácora General',
        'icono'       => 'fa-book',
        'descripcion' => 'Registro cronológico de eventos.',
        'ruta'        => '/modulos/auditoria/bitacora.php'
    ],
    [
        'titulo'      => 'Reportes de Auditoría',
        'icono'       => 'fa-chart-bar',
        'descripcion' => 'Reportes consolidados de auditoría.',
        'ruta'        => '/modulos/reportes/auditoria.php'
    ]
];

// --------------------------------------------------------------
// 6. Cargar vista
// --------------------------------------------------------------
require_once __DIR__ . '/../vistas/index.php';