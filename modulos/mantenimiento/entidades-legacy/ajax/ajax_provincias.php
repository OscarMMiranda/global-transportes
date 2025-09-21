<?php
  // archivo: /modulos/mantenimiento/entidades/ajax/ajax_provincias.php


ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. Cargar configuración y conexión
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

// 2. Encabezado HTML
header('Content-Type: text/html; charset=UTF-8');

// 3. Validar parámetro
$dep = isset($_GET['departamento_id']) ? (int)$_GET['departamento_id'] : 0;

// 4. Opción por defecto
echo '<option value="">— Selecciona —</option>';

// 5. Validar conexión
if (!($conn instanceof mysqli)) {
  echo '<option value="">[Error de conexión]</option>';
  exit;
}

// 6. Ejecutar consulta si el ID es válido
if ($dep > 0) {
  $stmt = $conn->prepare("SELECT id, nombre FROM provincias WHERE departamento_id = ? ORDER BY nombre");
  if ($stmt) {
    $stmt->bind_param("i", $dep);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($r = $res->fetch_assoc()) {
      printf('<option value="%d">%s</option>', $r['id'], htmlspecialchars($r['nombre']));
    }

    $stmt->close();
  } else {
    echo '<option value="">[Error en prepare]</option>';
  }
}
?>