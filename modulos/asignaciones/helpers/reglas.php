<?php
// archivo: /modulos/asignaciones/helpers/reglas.php

function puedeAsignar($conn, $conductorId, $tractoId, $carretaId, &$motivo) {
    $motivo = '';

    // Aquí irán las validaciones de disponibilidad, mantenimiento, viajes, etc.
    // Por ahora devolvemos true como base.

    return true;
}
