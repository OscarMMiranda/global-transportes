<?php
// archivo : /modulos/asistencias/reporte_mensual/core/rm_query.php
// Funciones para construir y ejecutar la consulta SQL del reporte mensual de asistencias

function rm_build_sql($f, &$params, &$types) {

    $sql = "
    SELECT 
        a.conductor_id,
        c.nombres,
        c.apellidos,
        a.fecha,
        t.codigo,
        t.descripcion,
        t.es_ausencia,
        t.es_feriado AS tipo_feriado,
        a.es_feriado AS registro_feriado
    FROM asistencia_conductores a
    INNER JOIN conductores c ON c.id = a.conductor_id
    INNER JOIN asistencia_tipos t ON t.id = a.tipo_id
    WHERE MONTH(a.fecha) = ?
      AND YEAR(a.fecha) = ?
    ";

    $params = array($f['mes'], $f['anio']);
    $types  = "ii";

    if ($f['empresa'] !== "") {
        $sql .= " AND c.empresa_id = ?";
        $params[] = $f['empresa'];
        $types .= "i";
    }

    if ($f['conductor'] !== "") {
        $sql .= " AND c.id = ?";
        $params[] = $f['conductor'];
        $types .= "i";
    }

    $sql .= " ORDER BY c.apellidos, c.nombres, a.fecha ASC";

    return $sql;
}


function rm_build_reporte_mensual_query($f)
{
    $where = array();

    if (!empty($f['anio'])) {
        $where[] = "YEAR(a.fecha) = " . intval($f['anio']);
    }

    if (!empty($f['mes'])) {
        $where[] = "MONTH(a.fecha) = " . intval($f['mes']);
    }

    if (!empty($f['conductor'])) {
        $where[] = "a.conductor_id = " . intval($f['conductor']);
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
            a.horas_trabajadas,
            a.horas_extra,
            a.estado,
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


function rm_ejecutar_query($cn, $sql, $params, $types) {

    $stmt = $cn->prepare($sql);

    if (!$stmt) {
        return array(
            'error' => true,
            'msg' => 'Error en prepare()',
            'sql' => $sql,
            'mysqli_error' => $cn->error
        );
    }

    // Bind dinámico compatible con PHP 5.6
    if (!empty($params)) {
        $bind = array($stmt, $types);
        foreach ($params as $k => $v) {
            $bind[] = &$params[$k];
        }
        call_user_func_array('mysqli_stmt_bind_param', $bind);
    }

    if (!$stmt->execute()) {
        return array(
            'error' => true,
            'msg' => 'Error en execute()',
            'sql' => $sql,
            'mysqli_error' => $stmt->error
        );
    }

    // Obtener metadata
    $meta = $stmt->result_metadata();
    if (!$meta) {
        return array(
            'error' => false,
            'res'   => array()
        );
    }

    $fields = array();
    $row = array();

    while ($field = $meta->fetch_field()) {
        $fields[] = $field->name;
        $row[$field->name] = null;
    }

    $bind_result = array();
    foreach ($fields as $field) {
        $bind_result[] = &$row[$field];
    }

    call_user_func_array(array($stmt, 'bind_result'), $bind_result);

    $result = array();

    while ($stmt->fetch()) {
        $result[] = array_map(function($v) { return $v; }, $row);
    }

    return array(
        'error' => false,
        'res'   => $result
    );
}
