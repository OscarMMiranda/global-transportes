<?php
    // archivo: modulos/asistencias/acciones/obtener_asistencia/query_builder.php
    // Función para obtener la consulta SQL de una asistencia
function query_obtener_asistencia() {
    return "
        SELECT 
            ac.id,
            ac.conductor_id,
            ac.tipo_id,
            t.codigo AS codigo_tipo,
            ac.hora_entrada,
            ac.hora_salida,
            ac.observacion,
            ac.fecha,
            ac.es_feriado,
            c.empresa_id,
            CONCAT(c.nombres, ' ', c.apellidos) AS conductor
        FROM asistencia_conductores ac
        INNER JOIN conductores c ON c.id = ac.conductor_id
        INNER JOIN asistencia_tipos t ON t.id = ac.tipo_id
        WHERE ac.id = ?
        LIMIT 1
    ";
}
