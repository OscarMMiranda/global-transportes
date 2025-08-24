<?php
// validaciones.php
// Funciones de validación para asignaciones compuestas (conductor + tracto + remolque)

/**
 * Verifica si el vehículo tiene la categoría esperada.
 * @param mysqli $conn
 * @param int $vehiculoId
 * @param int $categoriaEsperada (1 = tracto, 2 = remolque)
 * @return bool
 */
function validarCategoriaVehiculo($conn, $vehiculoId, $categoriaEsperada) {
    $sql = "SELECT categoria_id FROM vehiculos WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $vehiculoId);
        $stmt->execute();
        $stmt->bind_result($categoriaId);
        if ($stmt->fetch()) {
            $stmt->close();
            return intval($categoriaId) === intval($categoriaEsperada);
        }
        $stmt->close();
    }
    return false;
}

/**
 * Verifica si ya existe una asignación activa para el conductor, tracto o remolque.
 * Evita duplicidades y conflictos.
 * @param mysqli $conn
 * @param int $conductorId
 * @param int $tractoId
 * @param int $remolqueId
 * @return bool
 */
function validarAsignacionUnica($conn, $conductorId, $tractoId, $remolqueId) {
    $sql = "
        SELECT COUNT(*) FROM asignaciones_conductor
        WHERE estado_id = (SELECT id FROM estados WHERE nombre = 'activo')
        AND (
            conductor_id = ? OR
            tracto_id = ? OR
            remolque_id = ?
        )
    ";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iii", $conductorId, $tractoId, $remolqueId);
        $stmt->execute();
        $stmt->bind_result($cantidad);
        if ($stmt->fetch()) {
            $stmt->close();
            return intval($cantidad) === 0;
        }
        $stmt->close();
    }
    return false;
}

/**
 * Verifica que las fechas de inicio y fin sean válidas.
 * @param string $fechaInicio (formato YYYY-MM-DD)
 * @param string $fechaFin (formato YYYY-MM-DD)
 * @return bool
 */
function validarFechasAsignacion($fechaInicio, $fechaFin) {
    return strtotime($fechaInicio) <= strtotime($fechaFin);
}
?>
