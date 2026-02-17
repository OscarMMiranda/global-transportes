<?php
    // archivo  : /modulos/asistencias/reporte_mensual/ajax/helpers/execute_query.php
    // Función para ejecutar la consulta SQL del reporte mensual de asistencias con parámetros dinámicos

    function ejecutar_consulta($cn, $sql, $types, $params, $mes, $anio) {


    // Insertar mes y año al inicio
    array_unshift($params, $anio);
    array_unshift($params, $mes);

    // Preparar
    $stmt = $cn->prepare($sql);

    if (!$stmt) {
        return array(false, "Error al preparar consulta: " . $cn->error);
    }

    // Bind dinámico (PHP 5.6)
    $bind = array();
    $bind[] = $types;

    foreach ($params as $k => $v) {
        $bind[] = &$params[$k];
    }

    call_user_func_array(array($stmt, 'bind_param'), $bind);

    // Ejecutar
    $stmt->execute();

    $result = $stmt->get_result();

    $data = array();

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();

    return array(true, $data);
}
