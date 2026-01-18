<?php

function tiene_permiso($modulo, $accion) {

    if (!isset($_SESSION['rol_nombre'])) {
        return false;
    }

    $rol = strtolower($_SESSION['rol_nombre']);

    $mapa = require __DIR__ . '/mapa_permisos.php';

    // Si el rol no existe en el mapa
    if (!isset($mapa[$rol])) {
        return false;
    }

    // Si el módulo no está permitido para ese rol
    if (!isset($mapa[$rol][$modulo])) {
        return false;
    }

    // Si la acción no está permitida
    return in_array($accion, $mapa[$rol][$modulo]);
}