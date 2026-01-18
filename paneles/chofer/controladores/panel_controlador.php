<?php
// archivo: /paneles/chofer/controladores/panel_controlador.php

session_start();

// --------------------------------------------------------------
// 1. Validar sesión
// --------------------------------------------------------------
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
    header("Location: /login.php");
    exit;
}

// --------------------------------------------------------------
// 2. Validar que el rol sea CHOFER
// --------------------------------------------------------------
if ($_SESSION['rol_nombre'] !== 'chofer') {
    echo "Acceso denegado. Este panel es solo para choferes.";
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
    registrarActividad($conn, $_SESSION['usuario'], 'Acceso al Panel Chofer');
}

// --------------------------------------------------------------
// 5. Definir tarjetas del panel chofer
// --------------------------------------------------------------
$tarjetas = [
    [
        'titulo'      => 'Mis Viajes',
        'icono'       => 'fa-truck',
        'descripcion' => 'Ver rutas asignadas y estados.',
        'ruta'        => '/modulos/viajes/mis_viajes.php'
    ],
    [
        'titulo'      => 'Documentos',
        'icono'       => 'fa-id-card',
        'descripcion' => 'Licencias, SOAT, revisiones técnicas.',
        'ruta'        => '/modulos/documentos/'
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