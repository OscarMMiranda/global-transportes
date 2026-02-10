<?php
	//	 archivo: /modulos/asistencias/acciones/buscar_asistencias/resolver_periodo.php	
	// Función para resolver el periodo seleccionado en los filtros de búsqueda de asistencias	

	function resolver_periodo($periodo, $desde, $hasta) {

    	$hoy = date('Y-m-d');

    	switch ($periodo) {

    	    case 'hoy':
    	        return [$hoy, $hoy];

        	case 'ayer':
        	    $ayer = date('Y-m-d', strtotime('-1 day'));
        	    return [$ayer, $ayer];

        	case 'semana':
        	    return [
        	        date('Y-m-d', strtotime('monday this week')),
        	        date('Y-m-d', strtotime('sunday this week'))
        	    ];

        	case 'mes':
            	return [
            	    date('Y-m-01'),
            	    date('Y-m-t')
            	];

        	case 'rango':
            	return [$desde, $hasta];
    	}

    	return [null, null];
	}
