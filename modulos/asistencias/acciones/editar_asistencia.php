<?php
// archivo: /modulos/asistencias/acciones/editar_asistencia.php

error_log("⚡ EJECUTANDO editar_asistencia.php REAL");

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../../../includes/config.php';

$conn = getConnection();

if (!$conn) {
    echo json_encode([
        'ok' => false,
        'error' => 'No hay conexión con la base de datos'
    ]);
    exit;
}

// ============================================================
// CAPTURA DE DATOS
// ============================================================

$id      = isset($_POST['id']) ? intval($_POST['id']) : 0;
$tipo    = isset($_POST['tipo']) ? trim($_POST['tipo']) : "";
$entrada = isset($_POST['entrada']) && $_POST['entrada'] !== "" ? $_POST['entrada'] : null;
$salida  = isset($_POST['salida']) && $_POST['salida'] !== "" ? $_POST['salida'] : null;
$obs     = isset($_POST['obs']) ? trim($_POST['obs']) : "";

// ============================================================
// NORMALIZAR HORAS
// ============================================================

function normalizarHora($h) {
    if ($h === null) return null;
    if (preg_match('/^\d{2}:\d{2}$/', $h)) return $h . ':00';
    if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $h)) return $h;
    return null;
}

$entrada = normalizarHora($entrada);
$salida  = normalizarHora($salida);

if ($id <= 0) {
    echo json_encode(['ok' => false, 'error' => 'ID inválido']);
    exit;
}

// ============================================================
// SQL CORREGIDO (usa codigo_tipo, no tipo_id)
// ============================================================

$sql = "
    UPDATE asistencia_conductores
    SET 
        codigo_tipo  = ?,
        hora_entrada = ?,
        hora_salida  = ?,
        observacion  = ?,
        updated_at   = NOW()
    WHERE id = ?
";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo json_encode([
        'ok' => false,
        'error' => 'Error en prepare(): ' . mysqli_error($conn)
    ]);
    exit;
}

// tipo = string → bind con "s"
mysqli_stmt_bind_param($stmt, "ssssi", $tipo, $entrada, $salida, $obs, $id);

// ============================================================
// EJECUTAR UPDATE
// ============================================================

if (mysqli_stmt_execute($stmt)) {

    // ========================================================
    // REGISTRO DE HISTORIAL
    // ========================================================

    $usuario = $_SESSION['usuario'] ?? 'Sistema';

    $sqlHist = "INSERT INTO asistencia_historial 
                (asistencia_id, accion, usuario, detalle, fecha_hora)
                VALUES (?, 'editar', ?, 'Asistencia modificada', NOW())";

    $stmtHist = mysqli_prepare($conn, $sqlHist);

    if ($stmtHist) {
        mysqli_stmt_bind_param($stmtHist, "is", $id, $usuario);
        mysqli_stmt_execute($stmtHist);
        mysqli_stmt_close($stmtHist);
    }

    echo json_encode(['ok' => true]);
    exit;

} else {

    echo json_encode([
        'ok'    => false,
        'error' => mysqli_error($conn)
    ]);
    exit;
}
