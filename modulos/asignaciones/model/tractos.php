<?php
// archivo: /modulos/asignaciones/model/tractos.php

require_once __DIR__ . '/../../../includes/config.php';


/**
 * LISTAR TODOS LOS TRACTOS ACTIVOS
 */
function obtenerTractosTodos($conn) {

    $sql = "SELECT 
                v.id,
                v.placa
            FROM vehiculos v
            JOIN tipo_vehiculo tv ON tv.id = v.tipo_id
            WHERE tv.categoria_id = 1   -- TRACTOS
              AND v.activo = 1
            ORDER BY v.placa";

    $rs = mysqli_query($conn, $sql);

    $data = [];
    while ($row = mysqli_fetch_assoc($rs)) {
        $data[] = $row;
    }
    return $data;
}



/**
 * LISTAR TRACTOS DISPONIBLES (NO ASIGNADOS)
 */
function obtenerTractosDisponibles($conn) {

    $sql = "SELECT 
                v.id,
                v.placa
            FROM vehiculos v
            JOIN tipo_vehiculo tv ON tv.id = v.tipo_id
            WHERE tv.categoria_id = 1   -- TRACTOS
              AND v.activo = 1
              AND v.id NOT IN (
                    SELECT vehiculo_tracto_id
                    FROM asignaciones_conductor
                    WHERE estado_id = 1   -- asignación activa
              )
            ORDER BY v.placa";

    $rs = mysqli_query($conn, $sql);

    $data = [];
    while ($row = mysqli_fetch_assoc($rs)) {
        $data[] = $row;
    }
    return $data;
}
