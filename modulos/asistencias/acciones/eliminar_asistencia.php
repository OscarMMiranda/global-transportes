<?php
// archivo: /modulos/asistencias/acciones/eliminar_asistencia.php
header('Content-Type: application/json');

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

// Evitar doble session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode(['ok' => false, 'error' => 'ID inválido']);
    exit;
}

/* ============================================================
   1. Obtener datos previos ANTES de eliminar
   ============================================================ */
$sqlPrev = "SELECT * FROM asistencia_conductores WHERE id = ?";
$stmtPrev = mysqli_prepare($conn, $sqlPrev);
mysqli_stmt_bind_param($stmtPrev, "i", $id);
mysqli_stmt_execute($stmtPrev);
$resultPrev = mysqli_stmt_get_result($stmtPrev);
$prev = mysqli_fetch_assoc($resultPrev);

if (!$prev) {
    echo json_encode(['ok' => false, 'error' => 'Registro no encontrado']);
    exit;
}

/* ============================================================
   2. Registrar en asistencia_historial
   ============================================================ */
$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'DESCONOCIDO';

$detalle = "Registro eliminado: " . json_encode($prev, JSON_UNESCAPED_UNICODE);

$sqlHist = "INSERT INTO asistencia_historial (asistencia_id, accion, usuario, detalle)
            VALUES (?, 'eliminar', ?, ?)";

$stmtHist = mysqli_prepare($conn, $sqlHist);
mysqli_stmt_bind_param($stmtHist, "iss", $id, $usuario, $detalle);
mysqli_stmt_execute($stmtHist);

/* ============================================================
   3. Eliminar el registro real
   ============================================================ */
$sql = "DELETE FROM asistencia_conductores WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
$ok = mysqli_stmt_execute($stmt);

echo json_encode(['ok' => $ok]);
