<?php
// archivo: modulos/mantenimiento/tipo_documento/ajax/listar_inactivos.php
// listar_inactivos.php — Lista tipos de documento inactivos

// 1. Cargar configuración y conexión
$path = __DIR__ . '/../../../../includes/config.php';
if (!file_exists($path)) {
  die("❌ No se encontró el archivo de configuración: $path");
}
require_once $path;

// 2. Validar sesión
if (!isset($_SESSION['usuario_id'])) {
  die("❌ Sesión no iniciada.");
}

$conn = getConnection();
if (!$conn) {
  die("❌ Error de conexión.");
}

// 3. Consulta de tipos inactivos
$sql = "
  SELECT 
    td.id,
    cd.nombre AS categoria,
    td.nombre,
    td.descripcion,
    td.estado, -- ← necesario para renderizar correctamente
    td.duracion_meses,
    td.codigo_interno,
    td.color_etiqueta,
    td.icono,
    td.grupo,
    td.version
  FROM tipos_documento td
  JOIN categorias_documento cd ON td.categoria_id = cd.id
  WHERE td.estado = 0
  ORDER BY cd.nombre, td.nombre
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
  die("❌ Error en prepare: " . $conn->error);
}

$stmt->execute();
$resultado = $stmt->get_result();
$tipos = $resultado->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// 4. Salida en JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode($tipos);