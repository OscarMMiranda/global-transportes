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
// Nota: esta función se puede usar para validar que el ID de empresa existe antes de mostrar el reporte
function obtener_empresa_por_id($conn, $id)
{
    $sql = "SELECT id, razon_social FROM empresa WHERE id = ? LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    // Declarar variables antes para evitar advertencias del editor
    $rid = null;
    $razon_social = null;

    mysqli_stmt_bind_result($stmt, $rid, $razon_social);

    if (mysqli_stmt_fetch($stmt)) {
        mysqli_stmt_close($stmt);
        return array(
            'id' => $rid,
            'razon_social' => $razon_social
        );
    }

    mysqli_stmt_close($stmt);
    return null;
}
