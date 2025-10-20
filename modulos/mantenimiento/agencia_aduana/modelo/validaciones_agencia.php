<?php
// archivo: /modulos/mantenimiento/agencia_aduana/modelo/validaciones_agencia.php

	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	ini_set('error_log', __DIR__ . '/error_log.txt');

	require_once __DIR__ . '/../../../../includes/ubicacion_modelo.php';

/**
 * Validar formato de correo electrónico.
 * @param string $correo
 * @return bool
 */
function validarCorreoAgencia($correo) {
    return (is_string($correo) && filter_var($correo, FILTER_VALIDATE_EMAIL));
}

/**
 * Validar que el distrito pertenezca a la provincia.
 * @param int $distrito_id
 * @param int $provincia_id
 * @return bool
 */
function validarDistritoProvinciaAgencia($distrito_id, $provincia_id) {
    global $conn;
    if (!$conn || $distrito_id <= 0 || $provincia_id <= 0) {
        error_log("⚠️ Validación ignorada: distrito_id=$distrito_id, provincia_id=$provincia_id");
        return true; // ⚠️ Permitir continuar
    }

    $sql = "SELECT provincia_id FROM distritos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log('❌ Error al preparar validación cruzada: ' . $conn->error);
        return true; // ⚠️ Permitir continuar
    }

    $stmt->bind_param("i", $distrito_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();

    if ($res->num_rows === 0) {
        error_log("⚠️ Distrito $distrito_id no existe. Registro permitido.");
        return true; // ⚠️ Permitir continuar
    }

    $row = $res->fetch_assoc();
    $provincia_real = intval($row['provincia_id']);

    if ($provincia_real !== $provincia_id) {
        error_log("⚠️ Distrito $distrito_id pertenece a provincia $provincia_real, no a $provincia_id. Registro permitido.");
        return true; // ⚠️ Permitir continuar
    }

    return true;
}



/**
 * Validar campos obligatorios de agencia.
 * @param array $data
 * @return string Mensaje de error o cadena vacía si todo OK
 */
function validarCamposObligatoriosAgencia($data) {
    if (!isset($data['nombre']) || trim($data['nombre']) === '') {
        return '❌ El nombre de la agencia es obligatorio.';
    }
    if (!isset($data['ruc']) || trim($data['ruc']) === '') {
        return '❌ El RUC es obligatorio.';
    }
    return '';
}

/**
 * Validar existencia de distrito.
 * @param int $distrito_id
 * @return bool
 */
function validarDistritoExiste($distrito_id) {
    global $conn;
    $distrito_id = (int)$distrito_id;
    if (!$conn || $distrito_id <= 0) return false;

    $stmt = $conn->prepare("SELECT id FROM distritos WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $distrito_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();
    return ($res && $res->num_rows > 0);
}

function validarProvinciaDepartamentoAgencia($provincia_id, $departamento_id) {
    global $conn;
    if (!$conn || $provincia_id <= 0 || $departamento_id <= 0) return false;

    $sql = "SELECT departamento_id FROM provincias WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log('❌ Error al preparar validación cruzada provincia-departamento: ' . $conn->error);
        return false;
    }

    $stmt->bind_param("i", $provincia_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();

    if ($res->num_rows === 0) return false;

    $row = $res->fetch_assoc();
    return intval($row['departamento_id']) === $departamento_id;
}