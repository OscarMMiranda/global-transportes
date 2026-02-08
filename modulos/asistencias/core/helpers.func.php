<?php
// ============================================================
//  archivo: /modulos/asistencias/core/helpers.func.php
// ============================================================
// HELPERS DEL MÓDULO DE ASISTENCIAS
// ============================================================

function limpiarCadena($cadena)
{
    return htmlspecialchars(trim($cadena), ENT_QUOTES, 'UTF-8');
}

function debug($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

function json_ok($extra = [])
{
    echo json_encode(array_merge(['ok' => true], $extra));
    exit;
}

function json_error($msg)
{
    echo json_encode(['ok' => false, 'error' => $msg]);
    exit;
}
