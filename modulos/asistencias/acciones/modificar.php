<?php
// archivo: /modulos/asistencias/acciones/modificar.php

// ============================================================
// DEBUG ABSOLUTO
// ============================================================
$debug_file = __DIR__ . "/debug_modificar.log";

file_put_contents($debug_file, "====================\n", FILE_APPEND);
file_put_contents($debug_file, date("Y-m-d H:i:s") . " INICIO\n", FILE_APPEND);
file_put_contents($debug_file, "POST:\n" . print_r($_POST, true) . "\n", FILE_APPEND);

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../../../includes/config.php';
require __DIR__ . '/../core/helpers.func.php';
require __DIR__ . '/../core/asistencia.func.php';

$conn = $GLOBALS['db'];
if (!$conn instanceof mysqli) json_error('No hay conexión a la base de datos');

// POST (PHP 5.6 compatible)
$asistencia_id = isset($_POST['asistencia_id']) ? (int)$_POST['asistencia_id'] : 0;

// 🔥 FIX CRÍTICO: convertir código a MAYÚSCULAS
$codigo_tipo   = isset($_POST['codigo_tipo']) ? strtoupper(trim($_POST['codigo_tipo'])) : '';

$hora_entrada  = isset($_POST['hora_entrada'])  ? trim($_POST['hora_entrada']) : '';
$hora_salida   = isset($_POST['hora_salida'])   ? trim($_POST['hora_salida'])  : '';
$observacion   = isset($_POST['observacion'])   ? trim($_POST['observacion'])  : '';

if ($asistencia_id <= 0) json_error('ID inválido');
if ($codigo_tipo === '') json_error('Tipo obligatorio');

// resolver tipo_id
$id_tipo = tipo_id_por_codigo($conn, $codigo_tipo);
if ($id_tipo <= 0) json_error('Tipo inválido');

// ============================================================
// 1) OBTENER DATOS ANTERIORES PARA HISTORIAL
// ============================================================
$sql_old = "
    SELECT tipo_id, hora_entrada, hora_salida, observacion
    FROM asistencia_conductores
    WHERE id = ?
";

$stmt_old = $conn->prepare($sql_old);
$stmt_old->bind_param("i", $asistencia_id);
$stmt_old->execute();
$res_old = $stmt_old->get_result();
$old = $res_old->fetch_assoc();

if (!$old) json_error("Asistencia no encontrada");

// ============================================================
// 2) UPDATE dinámico PHP 5.6
// ============================================================
$campos = array();
$valores = array();
$tipos = "";

// tipo
$campos[] = "tipo_id = ?";
$valores[] = $id_tipo;
$tipos .= "i";

// tipo_codigo
$campos[] = "tipo_codigo = ?";
$valores[] = $codigo_tipo;
$tipos .= "s";

// hora entrada
if ($hora_entrada !== "") {
    $campos[] = "hora_entrada = ?";
    $valores[] = $hora_entrada;
    $tipos .= "s";
}

// hora salida
if ($hora_salida !== "") {
    $campos[] = "hora_salida = ?";
    $valores[] = $hora_salida;
    $tipos .= "s";
}

// observación
$campos[] = "observacion = ?";
$valores[] = $observacion;
$tipos .= "s";

// WHERE
$valores[] = $asistencia_id;
$tipos .= "i";

$sql = "
    UPDATE asistencia_conductores
    SET " . implode(", ", $campos) . "
    WHERE id = ?
";

$stmt = $conn->prepare($sql);
if (!$stmt) json_error("Error prepare: " . $conn->error);

// PHP 5.6 → bind_param dinámico
function refValues($arr) {
    $refs = array();
    foreach ($arr as $key => $value) {
        $refs[$key] = &$arr[$key];
    }
    return $refs;
}

$params = array_merge(array($tipos), $valores);
call_user_func_array(array($stmt, 'bind_param'), refValues($params));

if (!$stmt->execute()) {
    json_error("Error execute: " . $stmt->error);
}

// ============================================================
// 3) REGISTRAR HISTORIAL (solo si hubo cambios)
// ============================================================
$detalles = array();

// tipo
if ($old['tipo_id'] != $id_tipo) {
    $detalles[] = "Tipo: " . $old['tipo_id'] . " → " . $id_tipo;
}

// entrada
if ($old['hora_entrada'] != $hora_entrada) {
    $detalles[] = "Entrada: " . ($old['hora_entrada'] ?: '-') . " → " . ($hora_entrada ?: '-');
}

// salida
if ($old['hora_salida'] != $hora_salida) {
    $detalles[] = "Salida: " . ($old['hora_salida'] ?: '-') . " → " . ($hora_salida ?: '-');
}

// observación
if ($old['observacion'] != $observacion) {
    $detalles[] = "Obs: " . ($old['observacion'] ?: '-') . " → " . ($observacion ?: '-');
}

if (count($detalles) > 0) {

    $detalle_final = implode("\n", $detalles);

    $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Sistema';

    $sql_hist = "
        INSERT INTO asistencia_historial (asistencia_id, accion, usuario, detalle)
        VALUES (?, 'editar', ?, ?)
    ";

    $stmt_hist = $conn->prepare($sql_hist);
    $stmt_hist->bind_param("iss", $asistencia_id, $usuario, $detalle_final);
    $stmt_hist->execute();
}

// ============================================================
// 4) RESPUESTA FINAL
// ============================================================
json_ok(array(
    'mensaje' => 'Asistencia actualizada correctamente',
    'id'      => $asistencia_id
));
