<?php
// archivo: /paneles/cliente/controladores/panel_controlador.php

session_start();

// --------------------------------------------------------------
// 1. Validar sesión
// --------------------------------------------------------------
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
    header("Location: /login.php");
    exit;
}

// --------------------------------------------------------------
// 2. Validar que el rol sea CLIENTE
// --------------------------------------------------------------
if ($_SESSION['rol_nombre'] !== 'cliente') {
    echo "Acceso denegado. Este panel es solo para clientes.";
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
    registrarActividad($conn, $_SESSION['usuario'], 'Acceso al Panel Cliente');
}

// --------------------------------------------------------------
// 5. Definir tarjetas del panel cliente
// --------------------------------------------------------------
$tarjetas = [
    [
        'titulo'      => 'Mis Envíos',
        'icono'       => 'fa-box',
        'descripcion' => 'Consulta el estado de tus envíos.',
        'ruta'        => '/modulos/envios/mis_envios.php'
    ],
    [
        'titulo'      => 'Facturación',
        'icono'       => 'fa-file-invoice-dollar',
        'descripcion' => 'Descarga facturas y comprobantes.',
        'ruta'        => '/modulos/facturacion/'
    ],
    [
        'titulo'      => 'Mi Perfil',
        'icono'       => 'fa-user',
        'descripcion' => 'Actualiza tus datos personales.',
        'ruta'        => '/modulos/perfil/'
    ]
];

// --------------------------------------------------------------
// 6. Cargar vista
// --------------------------------------------------------------
require_once __DIR__ . '/../vistas/index.php';