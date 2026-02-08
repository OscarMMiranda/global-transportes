<?php
// ============================================================
//  archivo: /modulos/asistencias/core/fechas.func.php
// ============================================================
// FUNCIONES DEL SUBMÓDULO: FECHAS
// ============================================================

// Verificar si una fecha es feriado
function es_feriado($conn, $fecha)
{
    $sql = "SELECT 1 FROM calendario_laboral WHERE fecha = ? AND es_feriado = 1 LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $fecha);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    $es = mysqli_stmt_num_rows($stmt) > 0;

    mysqli_stmt_close($stmt);
    return $es;
}

// Verificar si una fecha es domingo
function es_domingo($fecha)
{
    return date('w', strtotime($fecha)) == 0;
}
