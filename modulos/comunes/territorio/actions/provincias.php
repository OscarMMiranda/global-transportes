<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

$departamento_id = isset($_GET['departamento_id']) ? intval($_GET['departamento_id']) : 0;

if (!is_object($conn) || $departamento_id <= 0) {
    echo '<option value="">Error de conexión o parámetro inválido</option>';
    return;
}

$sql = "SELECT id, nombre FROM provincias WHERE departamento_id = $departamento_id ORDER BY nombre ASC";
$res = $conn->query($sql);

if (!$res || $res->num_rows === 0) {
    echo '<option value="">Sin provincias disponibles</option>';
    return;
}

echo '<option value="">-- Seleccionar provincia --</option>';
while ($row = $res->fetch_assoc()) {
    echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['nombre']) . '</option>';
}
?>