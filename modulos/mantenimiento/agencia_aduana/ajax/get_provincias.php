<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
// Conexión (ruta absoluta para no pifiarla)
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';
header('Content-Type: text/html; charset=UTF-8');


// require_once __DIR__.'/../../../includes/conexion.php';
// header('Content-Type: text/html; charset=UTF-8');

$dep = isset($_POST['departamento_id']) ? (int)$_POST['departamento_id'] : 0;
echo '<option value="">— Selecciona —</option>';
if ($dep>0) {
  $stmt = $conn->prepare(
    "SELECT id, nombre FROM provincias WHERE departamento_id = ? ORDER BY nombre"
  );
  $stmt->bind_param("i", $dep);
  $stmt->execute();
  $res = $stmt->get_result();
  while ($r = $res->fetch_assoc()) {
    printf(
      '<option value="%d">%s</option>',
      $r['id'],
      htmlspecialchars($r['nombre'])
    );
  }
  $stmt->close();
}
