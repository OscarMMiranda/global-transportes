<?php
// archivo: /includes/ubicacion_modelo.php

require_once 'conexion.php';

if (!isset($_SESSION)) {
    session_start();
}

/**
 * Listar todos los departamentos.
 * @return array Lista de departamentos (id, nombre)
 */
function listarDepartamentos() {
    global $conn;
    if (!$conn) return [];

    $stmt = $conn->prepare("SELECT id, nombre FROM departamentos ORDER BY nombre");
    $stmt->execute();
    $resultado = $stmt->get_result();
    $res = [];
    while ($row = $resultado->fetch_assoc()) {
        $res[] = $row;
    }
    $stmt->close();
    return $res;
}

/**
 * Listar todas las provincias.
 * @return array Lista de provincias (id, nombre, departamento_id)
 */
function listarProvincias() {
    global $conn;
    if (!$conn) return [];

    $stmt = $conn->prepare("SELECT id, nombre, departamento_id FROM provincias ORDER BY nombre");
    $stmt->execute();
    $resultado = $stmt->get_result();
    $res = [];
    while ($row = $resultado->fetch_assoc()) {
        $res[] = $row;
    }
    $stmt->close();
    return $res;
}

/**
 * Listar todas las provincias de un departamento específico.
 * @param int $departamento_id ID del departamento
 * @return array Lista de provincias
 */
function listarProvinciasPorDepartamento($departamento_id) {
    global $conn;
    if (!$conn || $departamento_id <= 0) return [];

    $stmt = $conn->prepare("SELECT id, nombre FROM provincias WHERE departamento_id = ? ORDER BY nombre");
    $stmt->bind_param("i", $departamento_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $res = [];
    while ($row = $resultado->fetch_assoc()) {
        $res[] = $row;
    }
    $stmt->close();
    return $res;
}

/**
 * Listar todos los distritos.
 * @return array Lista de distritos (id, nombre, provincia_id)
 */
function listarDistritos() {
    global $conn;
    if (!$conn) return [];

    $stmt = $conn->prepare("SELECT id, nombre, provincia_id FROM distritos ORDER BY nombre");
    $stmt->execute();
    $resultado = $stmt->get_result();
    $res = [];
    while ($row = $resultado->fetch_assoc()) {
        $res[] = $row;
    }
    $stmt->close();
    return $res;
}

/**
 * Listar todos los distritos de una provincia específica.
 * @param int $provincia_id ID de la provincia
 * @return array Lista de distritos
 */
function listarDistritosPorProvincia($provincia_id) {
    global $conn;
    if (!$conn || $provincia_id <= 0) return [];

    $stmt = $conn->prepare("SELECT id, nombre FROM distritos WHERE provincia_id = ? ORDER BY nombre");
    $stmt->bind_param("i", $provincia_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $res = [];
    while ($row = $resultado->fetch_assoc()) {
        $res[] = $row;
    }
    $stmt->close();
    return $res;
}

/**
 * Validar si un distrito pertenece a una provincia.
 * @param int $distrito_id
 * @param int $provincia_id
 * @return bool
 */
function distritoPerteneceAProvincia($distrito_id, $provincia_id) {
    global $conn;

    // 🔐 Validación defensiva
    if (!$conn || $distrito_id <= 0 || $provincia_id <= 0) {
        error_log("❌ Validación fallida: distrito_id=$distrito_id, provincia_id=$provincia_id");
        return false;
    }

    // 📦 Consulta segura
    $stmt = $conn->prepare("SELECT id FROM distritos WHERE id = ? AND provincia_id = ? LIMIT 1");
    if (!$stmt) {
        error_log("❌ Error al preparar consulta: " . $conn->error);
        return false;
    }

    $stmt->bind_param("ii", $distrito_id, $provincia_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();

    // 📊 Trazabilidad del resultado
    if ($res && $res->num_rows > 0) {
        error_log("✅ Distrito $distrito_id pertenece a provincia $provincia_id");
        return true;
    } else {
        error_log("⚠️ Distrito $distrito_id NO pertenece a provincia $provincia_id");
        return false;
    }
}