<?php
    //  archivo :   /modulos/asistencias/reporte_mensual/backend/formatters.php

function rm_format_fecha($fecha)
{
    if (!$fecha || $fecha == '0000-00-00') return '';
    return date('d/m/Y', strtotime($fecha));
}

function rm_format_hora($hora)
{
    if ($hora === null || $hora === '' || $hora == '00:00:00') return '';
    return substr($hora, 0, 5);
}

function rm_format_estado($row)
{
    // Prioridad 1: feriado marcado en el registro
    if ((int)$row['es_feriado'] === 1) {
        return 'Feriado';
    }

    // Prioridad 2: feriado definido en el tipo
    if ((int)$row['tipo_es_feriado'] === 1) {
        return 'Feriado';
    }

    // Prioridad 3: descripción del tipo
    return $row['tipo_asistencia'];
}
