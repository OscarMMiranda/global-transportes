<?php
// ============================================================
//  archivo: /modulos/asistencias/core/empresas.func.php
// ============================================================
// FUNCIONES DEL SUBMÓDULO: EMPRESAS
// ============================================================

// Obtener lista de empresas
function obtener_empresas($conn)
{
    $sql = "SELECT id, razon_social FROM empresa ORDER BY razon_social";
    $res = $conn->query($sql);

    $lista = array();
    while ($row = $res->fetch_assoc()) {
        $lista[] = $row;
    }

    return $lista;
}

// Obtener empresa por ID
function obtener_empresa_por_id($conn, $id)
{
    $sql = "SELECT id, razon_social FROM empresa WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $data = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    return $data ?: null;
}
