<?php
    //  archivo : /modulos/asistencias/reporte_mensual/ajax/helpers/validar_filtros.php
    // Función para validar los filtros del reporte mensual de asistencias

    function validar_filtros($mes, $anio) {

    if ($mes === '' || $anio === '') {
        return "Mes y año son obligatorios";
    }

    if (!ctype_digit($mes) || !ctype_digit($anio)) {
        return "Parámetros inválidos";
    }

    return true;
}
