<?php
// ============================================================
//  archivo: /modulos/asistencias/core/matriz.func.php
// ============================================================
// FUNCIONES DEL SUBMÓDULO: MATRIZ MENSUAL
// ============================================================

function obtener_matriz($conn, $empresa_id, $mes, $anio)
{
    $sql = "
        SELECT 
            ac.conductor_id,
            c.nombres,
            ac.fecha,
            at.codigo AS tipo
        FROM asistencia_conductores ac
        INNER JOIN conductores c ON c.id = ac.conductor_id
        INNER JOIN asistencia_tipos at ON at.id = ac.tipo_id
        WHERE c.empresa_id = ?
        AND MONTH(ac.fecha) = ?
        AND YEAR(ac.fecha) = ?
        ORDER BY c.nombres, ac.fecha
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'iii', $empresa_id, $mes, $anio);
    mysqli_stmt_execute($stmt);

    // Declarar variables antes para evitar advertencias del editor
    $conductor_id = null;
    $nombres = null;
    $fecha = null;
    $tipo = null;

    mysqli_stmt_bind_result($stmt, $conductor_id, $nombres, $fecha, $tipo);

    $matriz = array();
    while (mysqli_stmt_fetch($stmt)) {
        $matriz[] = array(
            'conductor_id' => $conductor_id,
            'nombres'      => $nombres,
            'fecha'        => $fecha,
            'tipo'         => $tipo
        );
    }

    mysqli_stmt_close($stmt);
    return $matriz;
}
