<?php
// archivo : /modulos/mantenimiento/tipo_destinos/model.php

date_default_timezone_set('America/Lima');

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../includes/conexion.php';


// 📋 Listar todos los tipos de lugar
function obtenerTipos($conn) {
    $sql = "SELECT id, nombre, descripcion, estado FROM tipo_lugares ORDER BY nombre";
    $result = mysqli_query($conn, $sql);
    $tipos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $tipos[] = $row;
    }
    return $tipos;
}


// ➕ Insertar nuevo tipo (CORREGIDO)
function insertarTipo($conn, $nombre, $descripcion) {

    $sql = "INSERT INTO tipo_lugares (nombre, descripcion, estado) VALUES (?, ?, 'activo')";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        error_log("❌ Error en prepare (insertar): " . mysqli_error($conn));
        return false;
    }

    mysqli_stmt_bind_param($stmt, "ss", $nombre, $descripcion);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    error_log("✅ Tipo insertado: $nombre");
    return $ok;
}


// ✏️ Editar tipo existente (CORREGIDO)
function editarTipo($conn, $id, $nombre, $descripcion) {

    $sql = "UPDATE tipo_lugares SET nombre = ?, descripcion = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        error_log("❌ Error en prepare (editar): " . mysqli_error($conn));
        return false;
    }

    mysqli_stmt_bind_param($stmt, "ssi", $nombre, $descripcion, $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    error_log("✏️ Tipo editado: ID=$id, nombre=$nombre");
    return $ok;
}


// 🗑️ Soft delete: marcar como inactivo
function eliminarTipo($conn, $id) {
    $stmt = mysqli_prepare($conn, "UPDATE tipo_lugares SET estado = 'inactivo' WHERE id = ?");
    if (!$stmt) {
        error_log("❌ Error en prepare (soft delete): " . mysqli_error($conn));
        return false;
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    error_log("🗑️ Tipo marcado como inactivo: ID=$id");
    return true;
}


// 🔍 Validar si el nombre ya existe
function existeTipoLugar($conn, $nombre, $id = null) {

    if ($id) {
        $stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM tipo_lugares WHERE nombre = ? AND id != ?");
        mysqli_stmt_bind_param($stmt, "si", $nombre, $id);
    } else {
        $stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM tipo_lugares WHERE nombre = ?");
        mysqli_stmt_bind_param($stmt, "s", $nombre);
    }

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $total);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $total > 0;
}


// 🔒 Validar si el tipo está en uso por alguna entidad
function tipoLugarEnUso($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM entidades WHERE tipo_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $total);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $total > 0;
}


// 🔄 Reactivar tipo
function reactivarTipo($conn, $id) {
    $stmt = mysqli_prepare($conn, "UPDATE tipo_lugares SET estado = 'activo' WHERE id = ?");
    if (!$stmt) {
        error_log("❌ Error en prepare (reactivar): " . mysqli_error($conn));
        return false;
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    error_log("✅ Tipo reactivado: ID=$id");
    return true;
}
