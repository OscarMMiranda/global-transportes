<?php
	// archivo: /modulos/documentos_conductores/acciones/listar_historial_SQL.php
	
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

// Parámetros fijos para prueba
$conductor_id = 12;           // ← usa un ID real que tenga historial
$tipo_documento_id = 1;       // ← usa un tipo válido

$sql = "
    SELECT 
        id,
        version,
        archivo,
        fecha_subida,
        fecha_vencimiento,
        is_current
    FROM documentos
    WHERE entidad_tipo = 'conductor'
      AND entidad_id = ?
      AND tipo_documento_id = ?
      AND eliminado = 0
    ORDER BY version DESC
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("ERROR PREPARE: " . $conn->error);
}

$stmt->bind_param("ii", $conductor_id, $tipo_documento_id);

if (!$stmt->execute()) {
    die("ERROR EXECUTE: " . $stmt->error);
}

$result = $stmt->get_result();

if (!$result) {
    die("ERROR RESULT: " . $stmt->error);
}

while ($row = $result->fetch_assoc()) {
    print_r($row);
    echo "<br>";
}
