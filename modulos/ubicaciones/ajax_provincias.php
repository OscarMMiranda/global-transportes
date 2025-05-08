<?php
require_once '../../includes/conexion.php';

if (isset($_GET['departamento_id'])) {
    $dep_id = intval($_GET['departamento_id']);
    $stmt = $conn->prepare("SELECT id, nombre FROM provincias WHERE departamento_id = ? ORDER BY nombre");
    $stmt->bind_param("i", $dep_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">Seleccione</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['nombre']) . '</option>';
    }
}
?>
