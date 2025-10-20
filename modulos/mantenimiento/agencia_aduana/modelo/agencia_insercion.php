<?php
require_once __DIR__ . '/../../../../includes/conexion.php';
require_once __DIR__ . '/../../../../includes/funciones.php';

if (!isset($_SESSION)) {
    session_start();
}

/**
 * Insertar nueva agencia aduana.
 * @param array $datos
 * @return string Mensaje de error o cadena vacía si OK
 */
function insertarAgenciaNueva($datos) {
    global $conn;
    if (!$conn) return '❌ Conexión no disponible.';

    // Validación defensiva mínima
    if (!isset($datos['nombre']) || trim($datos['nombre']) === '') {
        return '❌ El nombre es obligatorio.';
    }
    if (!isset($datos['ruc']) || trim($datos['ruc']) === '') {
        return '❌ El RUC es obligatorio.';
    }

    $nombre          = trim($datos['nombre']);
    $ruc             = trim($datos['ruc']);
    $direccion       = trim(isset($datos['direccion']) ? $datos['direccion'] : '');
    $departamento_id = isset($datos['departamento_id']) ? intval($datos['departamento_id']) : 0;
    $provincia_id    = isset($datos['provincia_id']) ? intval($datos['provincia_id']) : 0;
    $distrito_id     = isset($datos['distrito_id']) ? intval($datos['distrito_id']) : 0;
    $telefono        = trim(isset($datos['telefono']) ? $datos['telefono'] : '');
    $correo_general  = trim(isset($datos['correo_general']) ? $datos['correo_general'] : '');
    $contacto        = trim(isset($datos['contacto']) ? $datos['contacto'] : '');

    error_log("📥 Insertar agencia: distrito $distrito_id, provincia $provincia_id, departamento $departamento_id");

    // 🔍 Validar que el distrito pertenezca a la provincia
    $sql = "SELECT provincia_id FROM distritos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log('❌ Error al preparar validación de distrito: ' . $conn->error);
        return '❌ Error interno al validar distrito.';
    }
    $stmt->bind_param("i", $distrito_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();

    if ($res->num_rows === 0) {
        return '❌ El distrito no existe.';
    }

    $row = $res->fetch_assoc();
    $provincia_real = intval($row['provincia_id']);

    if ($provincia_real !== $provincia_id) {
        error_log("❌ Distrito $distrito_id pertenece a provincia $provincia_real, no a $provincia_id");
        return '❌ El distrito no pertenece a la provincia seleccionada.';
    }

    // 🔍 Validar que la provincia pertenezca al departamento
    $sql = "SELECT departamento_id FROM provincias WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log('❌ Error al preparar validación de provincia: ' . $conn->error);
        return '❌ Error interno al validar provincia.';
    }
    $stmt->bind_param("i", $provincia_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();

    if ($res->num_rows === 0) {
        return '❌ La provincia no existe.';
    }

    $row = $res->fetch_assoc();
    $departamento_real = intval($row['departamento_id']);

    if ($departamento_real !== $departamento_id) {
        error_log("❌ Provincia $provincia_id pertenece a departamento $departamento_real, no a $departamento_id");
        return '❌ La provincia no pertenece al departamento seleccionado.';
    }

    // 🛠 Insertar nueva agencia
    try {
        $stmt = $conn->prepare("
            INSERT INTO agencias_aduanas (
                nombre, ruc, direccion, departamento_id, provincia_id, distrito_id,
                telefono, correo_general, contacto
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        if (!$stmt) {
            error_log('❌ Error al preparar INSERT: ' . $conn->error);
            return '❌ Error al preparar el registro.';
        }

        $stmt->bind_param(
            "sssiisssi",
            $nombre, $ruc, $direccion,
            $departamento_id, $provincia_id, $distrito_id,
            $telefono, $correo_general, $contacto
        );

        if (!$stmt->execute()) {
            $err = $stmt->error;
            $stmt->close();
            return "❌ Error al insertar: $err";
        }

        $newId = $stmt->insert_id;
        $stmt->close();

        registrarEnHistorial(
            $conn,
            $_SESSION['usuario'],
            "Agregó nueva agencia aduana (ID: $newId)",
            'agencias_aduanas',
            $_SERVER['REMOTE_ADDR']
        );

        return '';
    } catch (Exception $e) {
        error_log("❌ Error inesperado al insertar agencia: " . $e->getMessage());
        return '❌ Error inesperado al insertar agencia.';
    }
}