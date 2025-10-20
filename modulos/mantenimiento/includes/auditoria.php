<?php
// 🔐 auditoria.php - Registro de acciones en historial

/**
 * Registrar acción en historial de auditoría.
 *
 * @param mysqli  $conn     Conexión activa a la base de datos
 * @param string  $usuario  Usuario que realiza la acción
 * @param string  $accion   Descripción de la acción realizada
 * @param string  $modulo   Módulo afectado (ej: 'agencias_aduanas')
 * @param string  $ip       IP del usuario
 * @return bool             true si se registró correctamente, false si falló
 */
function registrarEnHistorial($conn, $usuario, $accion, $modulo, $ip) {
    // Validación defensiva
    if (!$conn || !$usuario || !$accion || !$modulo || !$ip) {
        error_log("❌ registrarEnHistorial: parámetros incompletos");
        return false;
    }

    // Preparar SQL
    $sql = "INSERT INTO historial (usuario, accion, modulo, ip, fecha) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("❌ registrarEnHistorial: error al preparar - " . $conn->error);
        return false;
    }

    // Ejecutar
    $stmt->bind_param("ssss", $usuario, $accion, $modulo, $ip);
    $ok = $stmt->execute();
    if (!$ok) {
        error_log("❌ registrarEnHistorial: error al ejecutar - " . $stmt->error);
    } else {
        error_log("✅ registrarEnHistorial: acción registrada - $usuario / $accion");
    }

    $stmt->close();
    return $ok;
}