<?php
// ============================================================
//  archivo: /modulos/asistencias/core/conductores.func.php
// ============================================================
// FUNCIONES DEL SUBMÓDULO: CONDUCTORES
// ============================================================

// Obtener lista de conductores por empresa
function obtener_conductores_por_empresa($conn, $empresa_id)
{
    $sql = "SELECT id, nombres 
            FROM conductores 
            WHERE empresa_id = ?
            ORDER BY nombres";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $empresa_id);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $id, $nombres);

    $lista = array();
    while (mysqli_stmt_fetch($stmt)) {
        $lista[] = array(
            'id' => $id,
            'nombres' => $nombres
        );
    }

    mysqli_stmt_close($stmt);
    return $lista;
}


// Obtener conductor por ID
function obtener_conductor_por_id($conn, $id)
{
    $sql = "SELECT id, nombres, empresa_id 
            FROM conductores 
            WHERE id = ? LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $rid, $nombres, $empresa_id);

    if (mysqli_stmt_fetch($stmt)) {
        mysqli_stmt_close($stmt);
        return array(
            'id' => $rid,
            'nombres' => $nombres,
            'empresa_id' => $empresa_id
        );
    }

    mysqli_stmt_close($stmt);
    return null;
}



// Obtener todos los conductores (uso general del módulo)
function obtener_conductores($conn)
{
    $sql = "SELECT id, nombres, apellidos, empresa_id
            FROM conductores
            WHERE activo = 1
            ORDER BY nombres, apellidos";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $id, $nombres, $apellidos, $empresa_id);

    $lista = array();
    while (mysqli_stmt_fetch($stmt)) {
        $lista[] = array(
            'id' => $id,
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'empresa_id' => $empresa_id
        );
    }

    mysqli_stmt_close($stmt);
    return $lista;
}

