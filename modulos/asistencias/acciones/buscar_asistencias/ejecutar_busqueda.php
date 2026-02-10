<?php
	// archivo: /modulos/asistencias/acciones/buscar_asistencias/ejecutar_busqueda.php
	// Función para ejecutar la consulta de búsqueda de asistencias y devolver los resultados
	
function ejecutar_busqueda($conn, $sql, $types, $conductor, $f_desde, $f_hasta, $tipo) {

    $stmt = mysqli_prepare($conn, $sql);

    if ($tipo === '') {
        mysqli_stmt_bind_param($stmt, $types, $conductor, $f_desde, $f_hasta);
    } else {
        mysqli_stmt_bind_param($stmt, $types, $conductor, $f_desde, $f_hasta, $tipo);
    }

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result(
        $stmt,
        $id,
        $fecha,
        $entrada,
        $salida,
        $obs,
        $tipo_desc
    );

    $data = [];

    while (mysqli_stmt_fetch($stmt)) {
        $data[] = [
            'id' => $id,
            'fecha' => $fecha,
            'hora_entrada' => $entrada,
            'hora_salida' => $salida,
            'observacion' => $obs,
            'tipo' => $tipo_desc
        ];
    }

    mysqli_stmt_close($stmt);

    return $data;
}
