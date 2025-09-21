<?php
// TestEntidad.php — Controlador mínimo para validación aislada

while (ob_get_level()) ob_end_clean();
ob_start();
header('Content-Type: application/json; charset=utf-8');

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';

if ($nombre === '') {
  while (ob_get_level()) ob_end_clean();
  echo json_encode(['estado'=>'error','mensaje'=>'El campo Nombre está vacío.']);
  exit;
}

// Validación de duplicado en tabla temporal
$check = $conn->prepare("SELECT id FROM entidades_test WHERE TRIM(LOWER(nombre)) = ? LIMIT 1");
$nombreNormalizado = strtolower(preg_replace('/\s+/', ' ', $nombre));
$check->bind_param("s", $nombreNormalizado);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
  $check->close();
  $conn->close();
  while (ob_get_level()) ob_end_clean();
  echo json_encode(['estado'=>'error','mensaje'=>'Ya existe ese nombre en entidades_test.']);
  exit;
}
$check->close();

// Inserción
$insert = $conn->prepare("INSERT INTO entidades_test (nombre, creado_en) VALUES (?, NOW())");
$insert->bind_param("s", $nombreNormalizado);

if (!$insert->execute()) {
  while (ob_get_level()) ob_end_clean();
  echo json_encode(['estado'=>'error','mensaje'=>'Error al guardar: '.$insert->error]);
  exit;
}

$id = $insert->insert_id;
$insert->close();
$conn->close();

while (ob_get_level()) ob_end_clean();
echo json_encode([
  'estado' => 'ok',
  'mensaje' => 'Nombre registrado correctamente.',
  'id' => $id,
  'nombre' => $nombreNormalizado
]);
exit;