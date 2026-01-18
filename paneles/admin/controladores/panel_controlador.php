<?php
// archivo: /paneles/admin/controladores/panel_controlador.php

session_start();

// --------------------------------------------------------------
// 1. Validación de sesión
// --------------------------------------------------------------
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
    header("Location: /login.php");
    exit;
}

// --------------------------------------------------------------
// 2. Validar que el rol sea ADMIN
// --------------------------------------------------------------
if ($_SESSION['rol'] !== 'admin' && $_SESSION['rol_nombre'] !== 'admin') {
    echo "Acceso denegado. Este panel es solo para administradores.";
    exit;
}

// --------------------------------------------------------------
// 3. Cargar configuración global
// --------------------------------------------------------------
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.php';

// Conexión
$conn = getConnection();

// --------------------------------------------------------------
// 4. Registrar acceso al panel (opcional, se puede integrar con auditoría ERP)
// --------------------------------------------------------------
if (function_exists('registrarActividad')) {
    registrarActividad($conn, $_SESSION['usuario'], 'Acceso al Panel Admin');
}

// --------------------------------------------------------------
// 5. Definir tarjetas del dashboard (modular y escalable)
// --------------------------------------------------------------
$tarjetas = [
    [
        'titulo'      => 'Gestión de Usuarios',
        'icono'       => 'fa-users',
        'descripcion' => 'Crear, editar o eliminar cuentas.',
        'ruta'        => '/modulos/usuarios/'
    ],
    [
        'titulo'      => 'Auditoría del Sistema',
        'icono'       => 'fa-clipboard-list',
        'descripcion' => 'Registro de actividad del sistema.',
        'ruta'        => '/modulos/seguridad/auditoria/'
    ],
    [
        'titulo'      => 'Reportes',
        'icono'       => 'fa-chart-line',
        'descripcion' => 'Estadísticas del sistema.',
        'ruta'        => '/paneles/admin/acciones/exportar_csv.php'
    ],
    [
        'titulo'      => 'ERP Dashboard',
        'icono'       => 'fa-rocket',
        'descripcion' => 'Acceso al módulo ERP completo.',
        'ruta'        => '../vistas/dashboard.php'
    ]
];

// --------------------------------------------------------------
// 6. Cargar la vista del panel
// --------------------------------------------------------------
require_once __DIR__ . '/../vistas/index.php';