<?php
// GuardarEntidad.php — Versión corregida y blindada

while (ob_get_level()) ob_end_clean(); // Limpieza total de buffers
ob_start();
header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

register_shutdown_function(function(){
  $err = error_get_last();
  if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
    while (ob_get_level()) ob_end_clean();
    echo json_encode([
      'estado' => 'error',
      'mensaje' => 'Error fatal: ' . $err['message']
    ]);
  }
});

// 🛡️ Captura de datos
$nombreOriginal = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$ruc            = isset($_POST['ruc'])    ? trim($_POST['ruc'])    : '';
$direccion      = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';
$departamentoId = isset($_POST['departamento_id']) ? (int)$_POST['departamento_id'] : 0;
$provinciaId    = isset($_POST['provincia_id'])    ? (int)$_POST['provincia_id']    : 0;
$distritoId     = isset($_POST['distrito_id'])     ? (int)$_POST['distrito_id']     : 0;
$tipoId         = isset($_POST['tipo_id'])         ? (int)$_POST['tipo_id']         : 0;

// 🧼 Normalización del nombre
$nombre = strtolower(trim(preg_replace('/\s+/', ' ', $nombreOriginal)));

// 🔍 Validaciones mínimas
if ($nombre === '') {
  while (ob_get_level()) ob_end_clean();
  echo json_encode(['estado'=>'error','mensaje'=>'El campo Nombre es obligatorio.']);
  exit;
}
if ($tipoId <= 0) {
  while (ob_get_level()) ob_end_clean();
  echo json_encode(['estado'=>'error','mensaje'=>'Debe seleccionar un tipo de lugar.']);
  exit;
}
if ($departamentoId <= 0 || $provinciaId <= 0 || $distritoId <= 0) {
  while (ob_get_level()) ob_end_clean();
  echo json_encode(['estado'=>'error','mensaje'=>'Debe seleccionar ubicación completa.']);
  exit;
}
if (!$conn) {
  while (ob_get_level()) ob_end_clean();
  echo json_encode(['estado'=>'error','mensaje'=>'Error de conexión con la base de datos.']);
  exit;
}

// 🔎 Validación de duplicado
$checkSql = "SELECT id FROM entidades WHERE TRIM(LOWER(nombre)) = ? AND estado = 'activo' LIMIT 1";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("s", $nombre);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
  $checkStmt->close();
  $conn->close();
  while (ob_get_level()) ob_end_clean();
  echo json_encode(['estado'=>'error','mensaje'=>'Ya existe una entidad con ese nombre.']);
  exit;
}
$checkStmt->close();

// 🧾 Inserción principal
$sql = "INSERT INTO entidades 
  (nombre, ruc, direccion, departamento_id, provincia_id, distrito_id, tipo_id, estado, creado_en)
  VALUES (?, ?, ?, ?, ?, ?, ?, 'activo', NOW())";

$stmt = $conn->prepare($sql);
if (!$stmt) {
  while (ob_get_level()) ob_end_clean();
  echo json_encode(['estado'=>'error','mensaje'=>'Error al preparar la consulta.']);
  exit;
}

$stmt->bind_param("sssiiii", $nombre, $ruc, $direccion, $departamentoId, $provinciaId, $distritoId, $tipoId);

if (!$stmt->execute()) {
  while (ob_get_level()) ob_end_clean();
  if ($stmt->errno === 1062) {
    echo json_encode(['estado'=>'error','mensaje'=>'Ya existe una entidad con ese nombre (duplicado en base de datos).']);
  } else {
    echo json_encode(['estado'=>'error','mensaje'=>'Error al guardar: '.$stmt->error]);
  }
  $stmt->close();
  $conn->close();
  exit;
}

$entidadId = $stmt->insert_id;

// 🧾 Auditoría
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'sistema';
$detalle = "Entidad creada: nombre=$nombreOriginal, ruc=$ruc, dir=$direccion, dep=$departamentoId, prov=$provinciaId, dist=$distritoId, tipo=$tipoId";

$logSql = "INSERT INTO auditoria_entidades (entidad_id, accion, usuario, fecha, detalle)
           VALUES (?, 'crear', ?, NOW(), ?)";
$logStmt = $conn->prepare($logSql);
if ($logStmt) {
  $logStmt->bind_param("iss", $entidadId, $usuario, $detalle);
  $logStmt->execute();
  $logStmt->close();
}

$stmt->close();
$conn->close();

while (ob_get_level()) ob_end_clean();
echo json_encode([
  'estado'  => 'ok',
  'mensaje' => 'Entidad registrada correctamente.',
  'id'      => $entidadId,
  'usuario' => $usuario,
  'fecha'   => date('Y-m-d H:i:s')
]);
exit;