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
    $res = mysqli_stmt_get_result($stmt);

    $lista = array();
    while ($row = mysqli_fetch_assoc($res)) {
        $lista[] = $row;
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
    $res = mysqli_stmt_get_result($stmt);

    $data = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    return $data ?: null;
}
