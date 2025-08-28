<?php
// archivo: /modulos/asignaciones_conductor/controlador.php

// 1) Carga de librerías
require_once __DIR__ . '/funciones.php';      // validarSesionAdmin()
require_once __DIR__ . '/modelo.php';         // getEstadoId(), getAsignaciones…
require_once __DIR__ . '/validaciones.php';   // validaciones específicas

// 2) Validar sesión
validarSesionAdmin();

// 3) Definir acciones válidas
$acciones_validas = array('list', 'create', 'store', 'edit', 'update', 'delete');

if (!in_array($action, $acciones_validas)) {
    include __DIR__ . '/vistas/error.php';
    exit;
}

// 4) Obtener conexión
$conn = getConnection();

// 5) Obtener estados
$estadoActivo     = getEstadoId($conn, 'activo');
$estadoFinalizado = getEstadoId($conn, 'finalizado');

// 6) Despacho de acciones
switch ($action) {
    case 'list':
        $asignacionesActivas   = getAsignacionesActivas($conn, $estadoActivo);
        $historialAsignaciones = getHistorialAsignaciones($conn, $estadoFinalizado);
        include __DIR__ . '/vistas/listado.php';
        break;

    case 'create':
        include __DIR__ . '/vistas/form_create.php';
        break;

    case 'edit':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $asignacion = getAsignacionPorId($conn, $id);
        $tractos     = getVehiculosPorCategoria($conn, 1); // tractos
        $remolques   = getVehiculosPorCategoria($conn, 2); // remolques
        $conductores = getConductores($conn);
        include __DIR__ . '/vistas/form_edit.php';
        break;

    case 'update':
        require_once __DIR__ . '/acciones/actualizar.php';
        break;

    case 'delete':
        require_once __DIR__ . '/acciones/eliminar.php';
        break;
}