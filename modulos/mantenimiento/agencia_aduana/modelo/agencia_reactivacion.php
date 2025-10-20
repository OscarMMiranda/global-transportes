<?php
// archivo: agencia_reactivacion.php

require_once __DIR__ . '/../../../../includes/conexion.php';
require_once __DIR__ . '/../../../../includes/funciones.php';

if (!isset($_SESSION)) {
    session_start();
}

/**
 * Reactivar agencia si existe en estado=0 por RUC.
 * @param string $ruc
 * @param array $datos
 * @return string Mensaje de error o cadena vacía si OK
 */
function reactivarAgenciaPorRUC($ruc, $datos) {
    global $conn;
    if (!$conn || $ruc === '') return '';

    $chk = $conn->prepare("SELECT id FROM agencias_aduanas WHERE ruc = ? AND estado = 0");
    $chk->bind_param("s", $ruc);
    $chk->execute();
    $res = $chk->get_result();
    $chk->close();

    if ($res->num_rows === 0) return '';

    $row = $res->fetch_assoc();
    $aid = (int)$row['id'];

    $stmt = $conn->prepare("
        UPDATE agencias_aduanas SET
            estado = 1,
            nombre = ?, direccion = ?,
            departamento_id = ?, provincia_id = ?, distrito_id = ?,
            telefono = ?, correo_general = ?, contacto = ?
        WHERE id = ?
    ");
    $stmt->bind_param(
        "ssiiisssi",
        $datos['nombre'], $datos['direccion'],
        $datos['departamento_id'], $datos['provincia_id'], $datos['distrito_id'],
        $datos['telefono'], $datos['correo_general'], $datos['contacto'],
        $aid
    );
    if (!$stmt->execute()) {
        $err = $stmt->error;
        $stmt->close();
        return "❌ Error al reactivar: $err";
    }
    $stmt->close();

    registrarEnHistorial(
        $conn,
        $_SESSION['usuario'],
        "Reactivó agencia aduana (ID: $aid)",
        'agencias_aduanas',
        $_SERVER['REMOTE_ADDR']
    );

    return '';
}