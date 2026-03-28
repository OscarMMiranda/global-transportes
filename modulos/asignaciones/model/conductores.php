<?php
// archivo: /modulos/asignaciones/model/conductores.php

require_once __DIR__ . '/../../../includes/config.php';


/**
 * LISTAR TODOS LOS CONDUCTORES ACTIVOS
 */
function obtenerConductoresTodos($conn) {

    $sql = "SELECT 
                id,
                CONCAT(nombres, ' ', apellidos) AS nombre
            FROM conductores
            WHERE activo = 1
            ORDER BY nombres, apellidos";

    $rs = mysqli_query($conn, $sql);

    $data = [];
    while ($row = mysqli_fetch_assoc($rs)) {
        $data[] = $row;
    }
    return $data;
}



/**
 * LISTAR CONDUCTORES DISPONIBLES (NO ASIGNADOS)
 */
function obtenerConductoresDisponibles($conn) {

    $sql = "SELECT 
                c.id,
                CONCAT(c.nombres, ' ', c.apellidos) AS nombre
            FROM conductores c
            WHERE c.activo = 1
              AND c.id NOT IN (
                    SELECT conductor_id
                    FROM asignaciones_conductor
                    WHERE estado_id = 1   -- asignación activa
              )
            ORDER BY c.nombres, c.apellidos";

    $rs = mysqli_query($conn, $sql);

    $data = [];
    while ($row = mysqli_fetch_assoc($rs)) {
        $data[] = $row;
    }
    return $data;
}
