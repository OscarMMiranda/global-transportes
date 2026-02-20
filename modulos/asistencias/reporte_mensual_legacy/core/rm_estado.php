<?php
// archivo : /modulos/asistencias/reporte_mensual/core/rm_estado.php
// Función para determinar el estado de asistencia basado en la fecha, código y otros campos relevantes

function rm_estado($row) {

    // Domingo
    if (date('w', strtotime($row['fecha'])) == 0) {
        return "DOMINGO";
    }

    // Feriado por registro o tipo
    if ($row['registro_feriado'] == 1 || $row['tipo_feriado'] == 1) {
        return "FERIADO";
    }

    // Vacaciones
    if ($row['codigo'] === "VA") return "VACACIONES";

    // Asistencia
    if ($row['codigo'] === "A") return "ASISTIO";

    // Faltas
    if ($row['codigo'] === "FI" || $row['codigo'] === "FJ") return "FALTO";

    // Cita médica
    if ($row['codigo'] === "ME") return "DESC_MED";

    // Permiso
    if ($row['codigo'] === "PE") return "PERMISO";

    return strtoupper($row['descripcion']);
}
