<?php
// archivo : /modulos/asistencias/reporte_mensual/core/rm_tabla.php
// Funciones para formatear los datos en formato tabla para el reporte mensual de asistencias

require_once 'rm_estado.php';

function rm_formato_tabla($res) {

    // $res ahora es un array, no un resultset
    file_put_contents("debug_tabla.txt", "FILAS: " . count($res));

    $data = array();

    foreach ($res as $row) {

        $data[] = array(
            'conductor' => $row['nombres'] . ' ' . $row['apellidos'],
            'fecha'     => $row['fecha'],
            'estado'    => rm_estado($row)
        );
    }

    return array(
        'ok'   => true,
        'data' => $data
    );
}
