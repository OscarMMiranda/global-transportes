<?php
// archivo: /modulos/asignaciones/model/carretas.php

require_once __DIR__ . '/../../../includes/config.php';


/**
 * LISTAR TODAS LAS CARRETAS ACTIVAS
 */
function obtenerCarretasTodos($conn) {

    $sql = "SELECT 
                v.id,
                v.placa
            FROM vehiculos v
            JOIN tipo_vehiculo tv ON tv.id = v.tipo_id
            WHERE tv.categoria_id = 2   -- CARRETAS / SEMIREMOLQUES
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
 * LISTAR CARRETAS DISPONIBLES (NO ASIGNADAS)
 */
function obtenerCarretasDisponibles($conn) {

    $sql = "SELECT 
                v.id,
                v.placa
            FROM vehiculos v
            JOIN tipo_vehiculo tv ON tv.id = v.tipo_id
            WHERE tv.categoria_id = 2   -- CARRETAS / SEMIREMOLQUES
              AND v.activo = 1
              AND v.id NOT IN (
                    SELECT vehiculo_remolque_id
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
