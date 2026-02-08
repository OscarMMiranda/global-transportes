<?php

// archivo: /modulos/asistencias/core/funciones.php
 
/* ============================================================
   FUNCIONES COMPARTIDAS DEL MÓDULO DE ASISTENCIAS
   ============================================================ */


/* ------------------------------------------------------------
   Obtener ID de tipo de asistencia por código (A, FI, VA, etc.)
   ------------------------------------------------------------ */
function tipo_id_por_codigo($conexion, $codigo)
{
    $sql = "SELECT id FROM asistencia_tipos WHERE codigo = ? LIMIT 1";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, 's', $codigo);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id);

    $id_tipo = null;
    if (mysqli_stmt_fetch($stmt)) {
        $id_tipo = $id;
    }

    mysqli_stmt_close($stmt);
    return $id_tipo;
}


/* ------------------------------------------------------------
   Verificar si una fecha es feriado
   ------------------------------------------------------------ */
function es_feriado($conexion, $fecha)
{
    $sql = "SELECT 1 FROM calendario_laboral WHERE fecha = ? AND es_feriado = 1 LIMIT 1";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, 's', $fecha);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    $es = mysqli_stmt_num_rows($stmt) > 0;

    mysqli_stmt_close($stmt);
    return $es;
}


/* ------------------------------------------------------------
   Verificar si una fecha es domingo
   ------------------------------------------------------------ */
function es_domingo($fecha)
{
    $ts = strtotime($fecha);
    return date('w', $ts) == 0; // 0 = domingo
}


/* ------------------------------------------------------------
   Obtener lista de conductores por empresa
   ------------------------------------------------------------ */
function obtener_conductores_por_empresa($conexion, $empresa_id)
{
    $sql = "SELECT id, nombres 
            FROM conductores 
            WHERE empresa_id = ?
            ORDER BY nombres";

    $stmt = mysqli_prepare($conexion, $sql);
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


/* ------------------------------------------------------------
   Obtener matriz mensual de asistencia
   ------------------------------------------------------------ */
function obtener_matriz($conexion, $empresa_id, $mes, $anio)
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

    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, 'iii', $empresa_id, $mes, $anio);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $matriz = array();
    while ($row = mysqli_fetch_assoc($res)) {
        $matriz[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $matriz;
}


// Obtener lista de empresas
function obtener_empresas($conn)
{
    $sql = "SELECT id, nombre FROM empresas ORDER BY nombre";
    $res = $conn->query($sql);

    $lista = array();
    while ($row = $res->fetch_assoc()) {
        $lista[] = $row;
    }

    return $lista;
}
?>
