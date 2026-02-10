<?php
    // archivo: /modulos/asistencias/acciones/buscar_asistencias/validar_filtros.php
    // Función para validar los filtros de búsqueda de asistencias
    function validar_filtros($conductor, $periodo, $desde, $hasta) {

		if ($conductor <= 0) {
			return ['ok' => false, 'error' => 'Debe seleccionar un conductor'];
    	}

    	if ($periodo === 'rango' && ($desde === '' || $hasta === '')) {
			return ['ok' => false, 'error' => 'Debe seleccionar un rango válido'];
		}

    	if (!in_array($periodo, ['hoy','ayer','semana','mes','rango'])) {
			return ['ok' => false, 'error' => 'Periodo inválido'];
		}

		return ['ok' => true];
	}
