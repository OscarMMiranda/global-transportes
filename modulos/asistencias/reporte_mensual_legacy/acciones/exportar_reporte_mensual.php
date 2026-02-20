<?php
// archivo : /modulos/asistencias/reporte_mensual/acciones/exportar_reporte_mensual.php

header('Content-Type: application/json');

require_once '../backend/db_connection.php';
require_once '../backend/query_builder.php';
require_once '../backend/formatters.php';

// Compatible con PHP 5.6
$filtros = array(
    'conductor' => isset($_POST['conductor']) ? $_POST['conductor'] : '',
    'mes'       => isset($_POST['mes']) ? $_POST['mes'] : '',
    'anio'      => isset($_POST['anio']) ? $_POST['anio'] : ''
);

$cn = rm_get_connection();
$sql = rm_build_reporte_mensual_query($filtros);
$result = mysqli_query($cn, $sql);

$data = array();

// Inicializar totales
$total_asistencias = 0;
$total_faltas = 0;
$total_tardanzas = 0;
$total_feriados = 0;

if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {

        // Determinar estado
        $estado = rm_format_estado($row);

        // Contabilizar
        if ($estado === 'Feriado') {
            $total_feriados++;
        } elseif ($row['es_ausencia'] == 1) {
            $total_faltas++;
        } elseif (stripos($estado, 'tard') !== false) {
            $total_tardanzas++;
        } else {
            $total_asistencias++;
        }

        // Agregar fila
        $data[] = array(
            'id'           => $row['id'],
            'fecha'        => rm_format_fecha($row['fecha']),
            'conductor'    => $row['conductor'],
            'estado'       => $estado,
            'hora_entrada' => rm_format_hora($row['hora_entrada']),
            'hora_salida'  => rm_format_hora($row['hora_salida']),
            'observacion'  => $row['observacion']
        );
    }
}

mysqli_close($cn);

echo json_encode(array(
    'data' => $data,
    'totales' => array(
        'asistencias' => $total_asistencias,
        'faltas'      => $total_faltas,
        'tardanzas'   => $total_tardanzas,
        'feriados'    => $total_feriados
    )
));
