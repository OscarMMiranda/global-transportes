<?php
//  archivo : /modulos/asistencias/reporte_mensual/ajax/helpers/json_response.php
// Funciones para generar respuestas JSON estándar para el módulo de reporte mensual de asistencias

function json_ok($data) {
    echo json_encode(array(
        'ok' => true,
        'data' => $data
    ));
    exit;
}

function json_error($msg, $extra = null) {
    $resp = array(
        'ok' => false,
        'msg' => $msg
    );

    if ($extra !== null) {
        $resp['extra'] = $extra;
    }

    echo json_encode($resp);
    exit;
}
