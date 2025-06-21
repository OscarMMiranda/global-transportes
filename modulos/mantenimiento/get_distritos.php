<?php
// get_distritos.php

// Incluir la conexión a la base de datos
require_once __DIR__ . '/../../includes/conexion.php';  // Ajusta la ruta según tu estructura

if (isset($_POST["provincia_id"])) {
    $provincia_id = intval($_POST["provincia_id"]);
    
    // Consulta para obtener distritos filtrados por provincia
    $query = "SELECT id, nombre FROM distritos WHERE provincia_id = ? ORDER BY nombre ASC";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Error en la preparación: " . $conn->error);
    }
    $stmt->bind_param("i", $provincia_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Iniciar las opciones con la opción por defecto
    $options = '<option value="">Elija un distrito</option>';
    while ($row = $result->fetch_assoc()) {
        $options .= '<option value="' . $row['id'] . '">' . htmlspecialchars($row['nombre']) . '</option>';
    }
    echo $options;
}
?>
