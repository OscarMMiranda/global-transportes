<?php
// archivo: /paneles/empleado/controladores/panel_controlador.php

session_start();

// --------------------------------------------------------------
// 1. Validar sesión
// --------------------------------------------------------------
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
    header("Location: /login.php");
    exit;
}

// --------------------------------------------------------------
// 2. Validar que el rol sea EMPLEADO
// --------------------------------------------------------------
if ($_SESSION['rol_nombre'] !== 'empleado') {
    echo "Acceso denegado. Este panel es solo para empleados.";
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
    registrarActividad($conn, $_SESSION['usuario'], 'Acceso al Panel Empleado');
}

// --------------------------------------------------------------
// 5. Definir tarjetas del panel empleado
// --------------------------------------------------------------
$tarjetas = [
    [
        'titulo'      => 'Mis Tareas',
        'icono'       => 'fa-tasks',
        'descripcion' => 'Ver tareas asignadas y estados.',
        'ruta'        => '/modulos/tareas/mis_tareas.php'
    ],
    [
        'titulo'      => 'Documentos Internos',
        'icono'       => 'fa-folder-open',
        'descripcion' => 'Acceso a documentos y manuales.',
        'ruta'        => '/modulos/documentos_internos/'
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