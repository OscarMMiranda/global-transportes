<?php
// archivo : /modulos/asistencias/reporte_mensual/core/rm_tabla.php
// Funciones para formatear los datos en formato tabla para el reporte mensual de asistencias

require_once 'rm_estado.php';

function rm_formato_tabla($res) {

    $data = array();

    foreach ($res as $row) {

        $data[] = array(
            'conductor'        => $row['conductor'],
            'fecha'            => $row['fecha'],
            'hora_entrada'     => $row['hora_entrada'],
            'hora_salida'      => $row['hora_salida'],
            'horas_trabajadas' => $row['horas_trabajadas'],
            'horas_extra'      => $row['horas_extra'],
            'estado'           => $row['estado']
        );
    }

    return array(
        'ok'   => true,
        'data' => $data
    );
}

