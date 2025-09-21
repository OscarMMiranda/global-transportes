<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

if (!is_object($conn)) {
    echo '<option value="">Error de conexión</option>';
    return;
}

$sql = "SELECT id, nombre FROM tipo_lugares ORDER BY nombre ASC";
$res = $conn->query($sql);

if (!$res || $res->num_rows === 0) {
    echo '<option value="">Sin tipos disponibles</option>';
    return;
}

echo '<option value="">-- Seleccionar tipo --</option>';
while ($row = $res->fetch_assoc()) {
    echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['nombre']) . '</option>';
}
?>