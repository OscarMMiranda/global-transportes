<?php
// archivo : /modulos/asistencias/reporte_mensual/ajax/helpers/json_response.php

function json_ok($data) {
    echo json_encode([
        'ok' => true,
        'data' => $data
    ]);
    exit;
}

function json_error($msg, $extra = null) {
    $resp = [
        'ok' => false,
        'msg' => $msg
    ];

    if ($extra !== null) {
        $resp['extra'] = $extra;
    }

    echo json_encode($resp);
    exit;
}
