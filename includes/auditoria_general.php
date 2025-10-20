<?php
// auditoria_general.php - Registro de historial genérico para cualquier entidad
// Compatible con PHP 5.6

// Este archivo debe incluirse en controladores que modifiquen registros:
// include_once('../includes/auditoria_general.php');

function registrarHistorial($entidad_id, $tabla_origen, $campo, $valor_anterior, $valor_nuevo, $usuario, $motivo, $tipo = 'MODIFICACION') {
    global $conn;

    // Validación defensiva
    if (!is_numeric($entidad_id) || empty($tabla_origen) || empty($campo)) {
        error_log("Historial no registrado: datos inválidos");
        return false;
    }

    // Sanitización básica
    $tabla_origen = substr($tabla_origen, 0, 100);
    $campo = substr($campo, 0, 100);
    $usuario = substr($usuario, 0, 100);
    $motivo = substr($motivo, 0, 255);

    // Preparar SQL
    $sql = "INSERT INTO historial_general 
            (entidad_id, tabla_origen, campo_modificado, valor_anterior, valor_nuevo, usuario_modificacion, motivo, tipo_accion, fecha_modificacion)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("isssssss", $entidad_id, $tabla_origen, $campo, $valor_anterior, $valor_nuevo, $usuario, $motivo, $tipo);
        $stmt->execute();
        $stmt->close();
        return true;
    } else {
        error_log("Error al registrar historial: " . $conn->error);
        return false;
    }
}
?>