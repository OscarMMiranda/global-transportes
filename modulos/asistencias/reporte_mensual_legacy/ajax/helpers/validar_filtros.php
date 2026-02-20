<?php
// archivo : /modulos/asistencias/reporte_mensual/ajax/helpers/validar_filtros.php

function validar_filtros($mes, $anio) {

    if ($mes === '' || $anio === '') {
        return "Mes y año son obligatorios";
    }

    if (!ctype_digit($mes) || !ctype_digit($anio)) {
        return "Parámetros inválidos";
    }

    return true;
}
