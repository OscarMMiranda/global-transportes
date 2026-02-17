<?php
    // archivo: modulos/asistencias/acciones/obtener_asistencia/validar_id.php
    // Validar el ID de la asistencia
function validar_id($id) {
    if ($id <= 0) {
        return ['ok' => false, 'error' => 'ID inválido'];
    }
    return ['ok' => true];
}
