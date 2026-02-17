<?php
// archivo : /modulos/asistencias/reporte_mensual/core/rm_dias.php
// Funciones para generar los días de un mes específico para el reporte mensual de asistencias

function rm_generar_dias_mes($mes, $anio) {

    $dias = array();

    $inicio = new DateTime("$anio-$mes-01");
    $fin = clone $inicio;
    $fin->modify('last day of this month');

    while ($inicio <= $fin) {
        $dias[] = $inicio->format('Y-m-d');
        $inicio->modify('+1 day');
    }

    return $dias;
}
