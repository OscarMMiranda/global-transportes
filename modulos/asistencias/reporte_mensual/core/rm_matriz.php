<?php
    // archivo: /modulos/asistencias/reporte_mensual/core/rm_matriz.php
    // Funciones para formatear los datos en formato matriz para el reporte mensual de asist

require_once 'rm_estado.php';

function rm_formato_matriz($res, $dias) {

    $conductores = array();

    while ($row = $res->fetch_assoc()) {

        $id = $row['conductor_id'];

        if (!isset($conductores[$id])) {
            $conductores[$id] = array(
                'id' => $id,
                'nombre' => $row['nombres'] . ' ' . $row['apellidos'],
                'asistencias' => array()
            );
        }

        $conductores[$id]['asistencias'][$row['fecha']] = rm_estado($row);
    }

    return array(
        'ok' => true,
        'dias' => $dias,
        'conductores' => array_values($conductores)
    );
}
