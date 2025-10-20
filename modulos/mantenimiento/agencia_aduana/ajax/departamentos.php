<?php
	//	archivo	:	/modulos/mantenimiento/agencia_aduana/ajax/departamentos.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';

$conn = getConnection();
if (!($conn instanceof mysqli)) {
    echo '<option value="">[Error de conexión]</option>';
    return;
}

$sql = "SELECT id, nombre FROM departamentos ORDER BY nombre ASC";
$res = $conn->query($sql);

if (!$res) {
    echo '<option value="">[Error de consulta]</option>';
    return;
}

echo '<option value="">-- Seleccionar departamento --</option>';
while ($row = $res->fetch_assoc()) {
    $id = intval($row['id']);
    $nombre = htmlspecialchars($row['nombre']);
    echo "<option value=\"$id\">$nombre</option>";
}
?>