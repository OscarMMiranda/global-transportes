<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

if (!is_object($conn)) {
    echo '<option value="">Error de conexión</option>';
    return;
}

$sql = "SELECT id, nombre FROM departamentos ORDER BY nombre ASC";
$res = $conn->query($sql);

if (!$res || $res->num_rows === 0) {
    echo '<option value="">Sin departamentos</option>';
    return;
}

echo '<option value="">-- Seleccionar departamento --</option>';
while ($row = $res->fetch_assoc()) {
    echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['nombre']) . '</option>';
}
?>