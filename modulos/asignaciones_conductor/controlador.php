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

    // Tractos con estado de ocupación
    $sql_tractos = "
        SELECT v.id, v.placa,
            CASE 
                WHEN EXISTS (
                    SELECT 1 FROM asignaciones_conductor ac
                    WHERE ac.vehiculo_tracto_id = v.id AND ac.estado_id = $estadoActivo
                ) THEN 1
                ELSE 0
            END AS ocupado
        FROM vehiculos v
        JOIN tipo_vehiculo tv ON v.tipo_id = tv.id
        WHERE tv.categoria_id = 1
          AND v.estado_id = (SELECT id FROM estado_vehiculo WHERE nombre = 'activo')
        ORDER BY v.placa ASC
    ";
    $tractos = [];
    $resTractos = $conn->query($sql_tractos);
    if ($resTractos) {
        while ($row = $resTractos->fetch_assoc()) {
            $tractos[] = $row;
        }
    }

    // Remolques con estado de ocupación
    $sql_remolques = "
        SELECT v.id, v.placa,
            CASE 
                WHEN EXISTS (
                    SELECT 1 FROM asignaciones_conductor ac
                    WHERE ac.vehiculo_remolque_id = v.id AND ac.estado_id = $estadoActivo
                ) THEN 1
                ELSE 0
            END AS ocupado
        FROM vehiculos v
        JOIN tipo_vehiculo tv ON v.tipo_id = tv.id
        WHERE tv.categoria_id = 2
          AND v.estado_id = (SELECT id FROM estado_vehiculo WHERE nombre = 'activo')
        ORDER BY v.placa ASC
    ";
    $remolques = [];
    $resRemolques = $conn->query($sql_remolques);
    if ($resRemolques) {
        while ($row = $resRemolques->fetch_assoc()) {
            $remolques[] = $row;
        }
    }

    // Conductores activos con estado de ocupación
    $sql_conductores = "
        SELECT c.id, c.nombres, c.apellidos,
            CASE 
                WHEN EXISTS (
                    SELECT 1 FROM asignaciones_conductor ac
                    WHERE ac.conductor_id = c.id AND ac.estado_id = $estadoActivo
                ) THEN 1
                ELSE 0
            END AS ocupado
        FROM conductores c
        WHERE c.activo = 1
        ORDER BY c.nombres ASC
    ";
    $conductores = [];
    $resConductores = $conn->query($sql_conductores);
    if ($resConductores) {
        while ($row = $resConductores->fetch_assoc()) {
            $conductores[] = $row;
        }
    }

    include __DIR__ . '/vistas/form_edit.php';
    break;

	

    	case 'update':
        	require_once __DIR__ . '/acciones/actualizar.php';
        	break;

    	case 'delete':
        	require_once __DIR__ . '/acciones/eliminar.php';
        	break;
		}