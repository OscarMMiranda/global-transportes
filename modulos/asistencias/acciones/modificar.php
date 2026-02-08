<?php
// archivo: /modulos/asistencias/acciones/modificar.php

require __DIR__ . '/../../../includes/config.php';

// Cargar funciones reales del módulo
require __DIR__ . '/../core/asistencia.func.php';
require __DIR__ . '/../core/empresas.func.php';
require __DIR__ . '/../core/conductores.func.php';
require __DIR__ . '/../core/fechas.func.php';
require __DIR__ . '/../core/matriz.func.php';
require __DIR__ . '/../core/helpers.func.php';

header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = getConnection();

/* ============================================================
   1) VALIDAR PARAMETROS
   ============================================================ */

$asistencia_id = intval($_POST['asistencia_id'] ?? 0);
$empresa_id    = intval($_POST['empresa_id'] ?? 0);
$conductor_id  = intval($_POST['conductor_id'] ?? 0);
$codigo_tipo   = $_POST['codigo_tipo'] ?? '';
$fecha         = $_POST['fecha'] ?? '';
$hora_entrada  = $_POST['hora_entrada'] ?? null;
$hora_salida   = $_POST['hora_salida'] ?? null;
$observacion   = $_POST['observacion'] ?? '';

if ($asistencia_id <= 0) {
    echo json_encode(['ok' => false, 'error' => 'ID de asistencia inválido']);
    exit;
}

if ($empresa_id <= 0 || $conductor_id <= 0 || $codigo_tipo === '' || $fecha === '') {
    echo json_encode(['ok' => false, 'error' => 'Faltan datos obligatorios']);
    exit;
}

/* ============================================================
   2) VALIDAR TIPO
   ============================================================ */

$id_tipo = tipo_id_por_codigo($conn, $codigo_tipo);

if ($id_tipo === null) {
    echo json_encode(['ok' => false, 'error' => 'Tipo de asistencia inválido']);
    exit;
}

/* ============================================================
   3) VALIDAR QUE LA ASISTENCIA EXISTA
   ============================================================ */

$sqlCheck = "SELECT id FROM asistencia_conductores WHERE id = ? LIMIT 1";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("i", $asistencia_id);
$stmtCheck->execute();
$stmtCheck->store_result();

if ($stmtCheck->num_rows === 0) {
    echo json_encode(['ok' => false, 'error' => 'La asistencia no existe']);
    exit;
}

$stmtCheck->close();

/* ============================================================
   4) VALIDAR DUPLICADO (MISMA FECHA + CONDUCTOR)
   ============================================================ */

$sqlDup = "SELECT id FROM asistencia_conductores 
           WHERE conductor_id = ? AND fecha = ? AND id != ? LIMIT 1";

$stmtDup = $conn->prepare($sqlDup);
$stmtDup->bind_param("isi", $conductor_id, $fecha, $asistencia_id);
$stmtDup->execute();
$stmtDup->store_result();

if ($stmtDup->num_rows > 0) {
    echo json_encode([
        'ok' => false,
        'error' => 'Ya existe una asistencia para este conductor en esta fecha'
    ]);
    exit;
}

$stmtDup->close();

/* ============================================================
   5) CALCULAR FERIADO
   ============================================================ */

$esFeriado = es_feriado($conn, $fecha) ? 1 : 0;

/* ============================================================
   6) ACTUALIZAR REGISTRO
   ============================================================ */

$sql = "UPDATE asistencia_conductores
        SET conductor_id = ?, fecha = ?, tipo_id = ?, 
            hora_entrada = ?, hora_salida = ?, 
            es_feriado = ?, observacion = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['ok' => false, 'error' => $conn->error]);
    exit;
}

$stmt->bind_param(
    "isissisi",
    $conductor_id,
    $fecha,
    $id_tipo,
    $hora_entrada,
    $hora_salida,
    $esFeriado,
    $observacion,
    $asistencia_id
);

if (!$stmt->execute()) {
    echo json_encode(['ok' => false, 'error' => $stmt->error]);
    exit;
}

/* ============================================================
   7) RESPUESTA FINAL
   ============================================================ */

echo json_encode(['ok' => true]);
