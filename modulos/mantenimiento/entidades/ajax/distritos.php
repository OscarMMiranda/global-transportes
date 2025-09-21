<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

$provincia_id = isset($_GET['provincia_id']) ? intval($_GET['provincia_id']) : 0;

if (!is_object($conn) || $provincia_id <= 0) {
    echo '<option value="">Error de conexión o parámetro inválido</option>';
    return;
}

$sql = "SELECT id, nombre FROM distritos WHERE provincia_id = $provincia_id ORDER BY nombre ASC";
$res = $conn->query($sql);

if (!$res || $res->num_rows === 0) {
    echo '<option value="">Sin distritos disponibles</option>';
    return;
}

echo '<option value="">-- Seleccionar distrito --</option>';
while ($row = $res->fetch_assoc()) {
    echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['nombre']) . '</option>';
}
?>