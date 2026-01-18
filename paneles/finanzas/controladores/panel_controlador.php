<?php
// archivo: /paneles/finanzas/controladores/panel_controlador.php

session_start();

// --------------------------------------------------------------
// 1. Validar sesión
// --------------------------------------------------------------
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
    header("Location: /login.php");
    exit;
}

// --------------------------------------------------------------
// 2. Validar que el rol sea FINANZAS
// --------------------------------------------------------------
if ($_SESSION['rol_nombre'] !== 'finanzas') {
    echo "Acceso denegado. Este panel es solo para el área de finanzas.";
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
    registrarActividad($conn, $_SESSION['usuario'], 'Acceso al Panel Finanzas');
}

// --------------------------------------------------------------
// 5. Definir tarjetas del panel finanzas
// --------------------------------------------------------------
$tarjetas = [
    [
        'titulo'      => 'Facturación',
        'icono'       => 'fa-file-invoice-dollar',
        'descripcion' => 'Emitir y consultar comprobantes.',
        'ruta'        => '/modulos/facturacion/'
    ],
    [
        'titulo'      => 'Cuentas por Cobrar',
        'icono'       => 'fa-hand-holding-dollar',
        'descripcion' => 'Control de pagos pendientes de clientes.',
        'ruta'        => '/modulos/cuentas/cobrar.php'
    ],
    [
        'titulo'      => 'Cuentas por Pagar',
        'icono'       => 'fa-money-bill-transfer',
        'descripcion' => 'Gestión de obligaciones y proveedores.',
        'ruta'        => '/modulos/cuentas/pagar.php'
    ],
    [
        'titulo'      => 'Ingresos y Egresos',
        'icono'       => 'fa-scale-balanced',
        'descripcion' => 'Registro y control de movimientos financieros.',
        'ruta'        => '/modulos/finanzas/movimientos.php'
    ],
    [
        'titulo'      => 'Reportes Financieros',
        'icono'       => 'fa-chart-pie',
        'descripcion' => 'Indicadores, balances y KPIs.',
        'ruta'        => '/modulos/reportes/finanzas.php'
    ]
];

// --------------------------------------------------------------
// 6. Cargar vista
// --------------------------------------------------------------
require_once __DIR__ . '/../vistas/index.php';