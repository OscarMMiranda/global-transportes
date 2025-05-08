<?php
require_once '../../includes/conexion.php';

if (isset($_GET['provincia_id'])) {
    $prov_id = intval($_GET['provincia_id']);
    $stmt = $conn->prepare("SELECT id, nombre FROM distritos WHERE provincia_id = ? ORDER BY nombre");
    $stmt->bind_param("i", $prov_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">Seleccione</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['nombre']) . '</option>';
    }
}
?>
