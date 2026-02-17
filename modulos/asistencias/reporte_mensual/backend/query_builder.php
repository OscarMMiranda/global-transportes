<?php
    //  archivo :   /modulos/asistencias/reporte_mensual/backend/query_builder.php

function rm_build_reporte_mensual_query($filtros)
{
    $where = array();

    if (!empty($filtros['anio'])) {
        $where[] = "YEAR(a.fecha) = " . intval($filtros['anio']);
    }

    if (!empty($filtros['mes'])) {
        $where[] = "MONTH(a.fecha) = " . intval($filtros['mes']);
    }

    if (!empty($filtros['conductor'])) {
        $where[] = "a.conductor_id = " . intval($filtros['conductor']);
    }

    if (empty($where)) {
        $where[] = "1 = 0"; // Seguridad
    }

    $where_sql = implode(' AND ', $where);

    $sql = "
        SELECT
            a.id,
            a.fecha,
            a.hora_entrada,
            a.hora_salida,
            a.es_feriado,
            a.observacion,

            CONCAT(c.nombres, ' ', c.apellidos) AS conductor,

            t.descripcion AS tipo_asistencia,
            t.es_ausencia,
            t.es_feriado AS tipo_es_feriado

        FROM asistencia_conductores a
        INNER JOIN conductores c ON c.id = a.conductor_id
        INNER JOIN asistencia_tipos t ON t.id = a.tipo_id

        WHERE $where_sql
        ORDER BY a.fecha ASC, c.apellidos ASC, c.nombres ASC
    ";

    return $sql;
}
