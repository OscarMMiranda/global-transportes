<?php
// archivo : /modulos/asistencias/reporte_mensual/core/rm_filtros.php
// Funciones para manejar los filtros del reporte mensual de asistencias

function rm_leer_filtros() {

    return array(
        'empresa'   => isset($_POST['empresa'])   ? trim($_POST['empresa'])   : '',
        'conductor' => isset($_POST['conductor']) ? trim($_POST['conductor']) : '',
        'mes'       => isset($_POST['mes'])       ? trim($_POST['mes'])       : '',
        'anio'      => isset($_POST['anio'])      ? trim($_POST['anio'])      : '',
        'vista'     => isset($_POST['vista'])     ? trim($_POST['vista'])     : 'tabla'
    );
}

function rm_validar_filtros($f) {

    if ($f['mes'] === '' || $f['anio'] === '') {
        return "Mes y año son obligatorios";
    }

    return "";
}
