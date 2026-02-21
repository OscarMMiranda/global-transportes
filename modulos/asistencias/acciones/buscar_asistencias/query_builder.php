<?php
	// archivo: /modulos/asistencias/acciones/buscar_asistencias/query_builder.php
	// Función para construir la consulta SQL de búsqueda de asistencias
	
function construir_query($tipo) {

    $sql = "
        SELECT 
            ac.id,
            ac.fecha,
            ac.hora_entrada,
            ac.hora_salida,
            ac.observacion,
            t.descripcion
        FROM asistencia_conductores ac
		INNER JOIN asistencia_tipos t ON t.codigo = ac.tipo_codigo
        WHERE ac.conductor_id = ?
          AND ac.fecha BETWEEN ? AND ?
    ";

    $params = [];
    $types  = "iss";

    if ($tipo !== '') {
        $sql .= " AND ac.tipo_codigo = ?
 		";
        $types .= "s";
    }

    $sql .= " ORDER BY ac.fecha ASC ";

    return [$sql, $types];
}
