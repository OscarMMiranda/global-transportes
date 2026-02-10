<?php
// archivo: /modulos/asistencias/acciones/modificar.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../../../includes/config.php';
require __DIR__ . '/../core/helpers.func.php';
require __DIR__ . '/../core/asistencia.func.php';
require __DIR__ . '/../core/empresas.func.php';
require __DIR__ . '/../core/conductores.func.php';
require __DIR__ . '/../core/fechas.func.php';
require __DIR__ . '/../core/matriz.func.php';

file_put_contents(__DIR__ . "/debug_modificar.log", date('Y-m-d H:i:s') . " INICIO\n", FILE_APPEND);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_error('Método no permitido');
}

// conexión REAL del sistema
$conn = $GLOBALS['db'];

// seguridad: si algo falló en config.php
if (!$conn instanceof mysqli) {
    json_error('No hay conexión a la base de datos');
}

// POST
$asistencia_id = isset($_POST['asistencia_id']) ? (int)$_POST['asistencia_id'] : 0;
$empresa_id    = isset($_POST['empresa_id'])    ? (int)$_POST['empresa_id']    : 0;
$conductor_id  = isset($_POST['conductor_id'])  ? (int)$_POST['conductor_id']  : 0;
$codigo_tipo   = isset($_POST['codigo_tipo'])   ? trim($_POST['codigo_tipo'])  : '';
$fecha         = isset($_POST['fecha'])         ? trim($_POST['fecha'])        : '';
$hora_entrada  = isset($_POST['hora_entrada'])  ? trim($_POST['hora_entrada']) : '';
$hora_salida   = isset($_POST['hora_salida'])   ? trim($_POST['hora_salida'])  : '';
$observacion   = isset($_POST['observacion'])   ? trim($_POST['observacion'])  : '';

$debugData = [
    'asistencia_id' => $asistencia_id,
    'empresa_id'    => $empresa_id,
    'conductor_id'  => $conductor_id,
    'codigo_tipo'   => $codigo_tipo,
    'fecha'         => $fecha,
    'hora_entrada'  => $hora_entrada,
    'hora_salida'   => $hora_salida,
    'observacion'   => $observacion,
];
file_put_contents(__DIR__ . "/debug_modificar.log", print_r($debugData, true) . "\n", FILE_APPEND);

// validación
if ($asistencia_id <= 0) json_error('ID de asistencia inválido');
if ($empresa_id <= 0 || $conductor_id <= 0) json_error('Empresa o conductor inválidos');
if ($codigo_tipo === '' || $fecha === '') json_error('Tipo y fecha son obligatorios');

// resolver tipo_id
$id_tipo = tipo_id_por_codigo($conn, $codigo_tipo);

file_put_contents(
    __DIR__ . "/debug_modificar.log",
    "tipo_id_por_codigo => codigo_tipo={$codigo_tipo}, id_tipo={$id_tipo}\n",
    FILE_APPEND
);

if ($id_tipo <= 0) {
    json_error('Tipo de asistencia inválido');
}

// 10) Construir SQL del UPDATE
$sql_update = "
    UPDATE asistencia_conductores
    SET 
        conductor_id = ?,
        fecha = ?,
        tipo_id = ?,
        hora_entrada = ?,
        hora_salida = ?,
        observacion = ?
    WHERE id = ?
";

// preparar
$stmt = $conn->prepare($sql_update);
if (!$stmt) {
    json_error("Error al preparar UPDATE: " . $conn->error);
}

// bind_param (7 parámetros)
$stmt->bind_param(
    "isisssi",
    $conductor_id,
    $fecha,
    $id_tipo,
    $hora_entrada,
    $hora_salida,
    $observacion,
    $asistencia_id
);

// ejecutar
if (!$stmt->execute()) {
    json_error("Error al ejecutar UPDATE: " . $stmt->error);
}

// verificar filas afectadas
if ($stmt->affected_rows < 0) {
    json_error("No se pudo actualizar la asistencia");
}

$stmt->close();

// respuesta final
json_ok([
    'mensaje' => 'Asistencia actualizada correctamente',
    'id'      => $asistencia_id
]);

