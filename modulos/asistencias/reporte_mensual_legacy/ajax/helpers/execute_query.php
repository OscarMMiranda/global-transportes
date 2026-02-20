<?php
// archivo : /modulos/asistencias/reporte_mensual/ajax/helpers/execute_query.php

function ejecutar_consulta($cn, $sql, $types, $params, $mes, $anio) {

    array_unshift($params, $anio);
    array_unshift($params, $mes);

    $stmt = $cn->prepare($sql);

    if (!$stmt) {
        return [false, "Error al preparar consulta: " . $cn->error];
    }

    $bind = [];
    $bind[] = $types;

    foreach ($params as $k => $v) {
        $bind[] = &$params[$k];
    }

    call_user_func_array([$stmt, 'bind_param'], $bind);

    $stmt->execute();

    $result = $stmt->get_result();

    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();

    return [true, $data];
}
