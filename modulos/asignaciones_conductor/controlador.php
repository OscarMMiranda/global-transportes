<?php
	// 	archivo		:	/modulos/asignaciones_conductor/controlador.php


	// 	1)	Carga de configuraciones y librerías
		// require_once __DIR__ . '/../../includes/config.php';  // define getConnection()
	
		require_once __DIR__ . '/funciones.php';              // validarSesionAdmin()
		require_once __DIR__ . '/modelo.php';                 // getEstadoId(), getAsignaciones…
		require_once __DIR__ . '/validaciones.php';


    // 4) Obtener la conexión
    	// $conn = getConnection();

	// 	2) 	Validar que sea usuario admin
		validarSesionAdmin();

	// 3) Definir acciones válidas
	$acciones_validas = ['list', 'create', 'store', 'delete'];

	if (!in_array($action, $acciones_validas)) {
    	include __DIR__ . '/vistas/error.php';
    	exit;
		}

	// 5) Obtener los IDs de estado
		$estadoActivo     = getEstadoId($conn, 'activo');
		$estadoFinalizado = getEstadoId($conn, 'finalizado');

	// 6) Obtener datos
		$asignacionesActivas   = getAsignacionesActivas($conn, $estadoActivo);
		$historialAsignaciones = getHistorialAsignaciones($conn, $estadoFinalizado);

	// 7) Cargar la vista
		// include __DIR__ . '/vista_listado.php';


	// 4) Despacho de acciones
switch ($action) {
    case 'list':
        $estadoActivo     = getEstadoId($conn, 'activo');
        $estadoFinalizado = getEstadoId($conn, 'finalizado');
        $asignacionesActivas   = getAsignacionesActivas($conn, $estadoActivo);
        $historialAsignaciones = getHistorialAsignaciones($conn, $estadoFinalizado);
        include __DIR__ . '/vistas/listado.php';
        break;

    case 'create':
        include __DIR__ . '/vistas/form_create.php';
        break;

    case 'store':
        require_once __DIR__ . '/acciones/guardar.php';
        break;

    case 'delete':
        require_once __DIR__ . '/acciones/eliminar.php';
        break;

	}

