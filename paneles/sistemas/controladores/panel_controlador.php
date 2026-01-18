<?php
// archivo: /paneles/sistemas/controladores/panel_controlador.php

session_start();

// --------------------------------------------------------------
// 1. Validar sesión
// --------------------------------------------------------------
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
    header("Location: /login.php");
    exit;
}

// --------------------------------------------------------------
// 2. Validar que el rol sea SISTEMAS
// --------------------------------------------------------------
if ($_SESSION['rol_nombre'] !== 'sistemas') {
    echo "Acceso denegado. Este panel es solo para el área de sistemas.";
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
    registrarActividad($conn, $_SESSION['usuario'], 'Acceso al Panel Sistemas');
}

// --------------------------------------------------------------
// 5. Definir tarjetas del panel sistemas
// --------------------------------------------------------------
$tarjetas = [
    [
        'titulo'      => 'Usuarios y Roles',
        'icono'       => 'fa-users-cog',
        'descripcion' => 'Administración de usuarios, roles y permisos.',
        'ruta'        => '/modulos/seguridad/usuarios/'
    ],
    [
        'titulo'      => 'Control de Accesos',
        'icono'       => 'fa-key',
        'descripcion' => 'Gestión de accesos y políticas de seguridad.',
        'ruta'        => '/modulos/seguridad/accesos.php'
    ],
    [
        'titulo'      => 'Auditoría del Sistema',
        'icono'       => 'fa-clipboard-list',
        'descripcion' => 'Registro de actividad y eventos críticos.',
        'ruta'        => '/modulos/seguridad/auditoria/'
    ],
    [
        'titulo'      => 'Configuración General',
        'icono'       => 'fa-cogs',
        'descripcion' => 'Parámetros globales del sistema.',
        'ruta'        => '/modulos/configuracion/'
    ],
    [
        'titulo'      => 'Monitoreo Técnico',
        'icono'       => 'fa-server',
        'descripcion' => 'Estado del servidor, logs y recursos.',
        'ruta'        => '/modulos/monitoreo/'
    ]
];

// --------------------------------------------------------------
// 6. Cargar vista
// --------------------------------------------------------------
require_once __DIR__ . '/../vistas/index.php';