<?php
// archivo: /paneles/seguridad/controladores/panel_controlador.php

session_start();

// --------------------------------------------------------------
// 1. Validar sesión
// --------------------------------------------------------------
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
    header("Location: /login.php");
    exit;
}

// --------------------------------------------------------------
// 2. Validar que el rol sea SEGURIDAD
// --------------------------------------------------------------
if ($_SESSION['rol_nombre'] !== 'seguridad') {
    echo "Acceso denegado. Este panel es solo para el área de seguridad.";
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
    registrarActividad($conn, $_SESSION['usuario'], 'Acceso al Panel Seguridad');
}

// --------------------------------------------------------------
// 5. Definir tarjetas del panel seguridad
// --------------------------------------------------------------
$tarjetas = [
    [
        'titulo'      => 'Auditoría de Accesos',
        'icono'       => 'fa-clipboard-list',
        'descripcion' => 'Registro de accesos y eventos críticos.',
        'ruta'        => '/modulos/seguridad/auditoria/'
    ],
    [
        'titulo'      => 'Políticas de Seguridad',
        'icono'       => 'fa-shield-halved',
        'descripcion' => 'Gestión de políticas y restricciones.',
        'ruta'        => '/modulos/seguridad/politicas.php'
    ],
    [
        'titulo'      => 'Bloqueos y Alertas',
        'icono'       => 'fa-triangle-exclamation',
        'descripcion' => 'Gestión de bloqueos, alertas y anomalías.',
        'ruta'        => '/modulos/seguridad/bloqueos.php'
    ],
    [
        'titulo'      => 'Bitácora del Sistema',
        'icono'       => 'fa-book',
        'descripcion' => 'Revisión de logs y bitácoras.',
        'ruta'        => '/modulos/seguridad/bitacora.php'
    ],
    [
        'titulo'      => 'Usuarios Críticos',
        'icono'       => 'fa-user-shield',
        'descripcion' => 'Control de cuentas privilegiadas.',
        'ruta'        => '/modulos/seguridad/usuarios_criticos.php'
    ]
];

// --------------------------------------------------------------
// 6. Cargar vista
// --------------------------------------------------------------
require_once __DIR__ . '/../vistas/index.php';