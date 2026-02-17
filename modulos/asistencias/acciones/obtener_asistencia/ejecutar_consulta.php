<?php
    // archivo: modulos/asistencias/acciones/obtener_asistencia/ejecutar_consulta.php
    // Función para ejecutar la consulta SQL de una asistencia  
    // Requiere conexión, consulta SQL y ID de la asistencia
    
function ejecutar_consulta_asistencia($conn, $sql, $id) {

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        return ['ok' => false, 'error' => mysqli_error($conn)];
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(
        $stmt,
        $rid,
        $rconductor_id,
        $rtipo_id,
        $rcodigo_tipo,
        $rentrada,
        $rsalida,
        $robs,
        $rfecha,
        $res_feriado,
        $rempresa_id,
        $rconductor
    );

    if (!mysqli_stmt_fetch($stmt)) {
        return ['ok' => false, 'error' => 'Registro no encontrado'];
    }

    mysqli_stmt_close($stmt);

    return [
        'ok' => true,
        'data' => [
            'id'           => $rid,
            'conductor_id' => $rconductor_id,
            'tipo_id'      => $rtipo_id,
            'codigo_tipo'  => $rcodigo_tipo,
            'hora_entrada' => $rentrada,
            'hora_salida'  => $rsalida,
            'observacion'  => $robs,
            'fecha'        => $rfecha,
            'es_feriado'   => $res_feriado,
            'empresa_id'   => $rempresa_id,
            'conductor'    => $rconductor
        ]
    ];
}
