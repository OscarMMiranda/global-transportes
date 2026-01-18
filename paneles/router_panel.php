<?php
// archivo: /paneles/router_panel.php

session_start();

// --------------------------------------------------------------
// 1. Validar sesión
// --------------------------------------------------------------
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol_nombre'])) {
    header("Location: /login.php");
    exit;
}

// --------------------------------------------------------------
// 2. Mapa de roles → panel (actualizado)
// --------------------------------------------------------------
$paneles = [
    'admin'         => '/paneles/admin/controladores/dashboard_controlador.php',
    'chofer'        => '/paneles/chofer/controladores/dashboard_controlador.php',
    'cliente'       => '/paneles/cliente/controladores/dashboard_controlador.php',
    'empleado'      => '/paneles/empleado/controladores/dashboard_controlador.php',
    'supervisor'    => '/paneles/supervisor/controladores/dashboard_controlador.php',
    'operaciones'   => '/paneles/operaciones/controladores/dashboard_controlador.php',
    'finanzas'      => '/paneles/finanzas/controladores/dashboard_controlador.php',
    'gerencia'      => '/paneles/gerencia/controladores/dashboard_controlador.php',
    'logistica'     => '/paneles/logistica/controladores/dashboard_controlador.php',
    'mantenimiento' => '/paneles/mantenimiento/controladores/dashboard_controlador.php',
    'rrhh'          => '/paneles/rrhh/controladores/dashboard_controlador.php',
    'comercial'     => '/paneles/comercial/controladores/dashboard_controlador.php',
    'sistemas'      => '/paneles/sistemas/controladores/dashboard_controlador.php',
    'seguridad'     => '/paneles/seguridad/controladores/dashboard_controlador.php',
    'atencion'      => '/paneles/atencion/controladores/dashboard_controlador.php',
    'auditoria'     => '/paneles/auditoria/controladores/dashboard_controlador.php'
];

// --------------------------------------------------------------
// 3. Obtener rol actual
// --------------------------------------------------------------
$rol = strtolower($_SESSION['rol_nombre']);

// --------------------------------------------------------------
// 4. Validar existencia del panel
// --------------------------------------------------------------
if (!isset($paneles[$rol])) {
    echo "No existe un panel asignado para el rol: " . htmlspecialchars($rol);
    exit;
}

// --------------------------------------------------------------
// 5. Redirigir al panel correspondiente
// --------------------------------------------------------------
header("Location: " . $paneles[$rol]);
exit;