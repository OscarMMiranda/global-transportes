<?php
// archivo : /modulos/asistencias/reporte_mensual/ajax/helpers/build_query.php

function build_query($conductor) {

    $sql = "
        SELECT 
            a.fecha,
            CONCAT(c.nombres, ' ', c.apellidos) AS conductor,
            a.hora_entrada,
            a.hora_salida,
            a.horas_trabajadas,
            a.horas_extra,
            a.estado
        FROM asistencias a
        INNER JOIN conductores c ON c.id = a.id_conductor
        WHERE MONTH(a.fecha) = ?
          AND YEAR(a.fecha) = ?
    ";

    $types = "ii";
    $params = [];

    if ($conductor !== '') {
        $sql .= " AND a.id_conductor = ? ";
        $types .= "i";
        $params[] = $conductor;
    }

    $sql .= " ORDER BY a.fecha ASC";

    return [$sql, $types, $params];
}
