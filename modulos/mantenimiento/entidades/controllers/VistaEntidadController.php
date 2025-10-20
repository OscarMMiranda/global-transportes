<?php
// VistaEntidadController.php — muestra la ficha completa de una entidad

$entidad_id = isset($_GET['entidad_id']) ? intval($_GET['entidad_id']) : 0;
if ($entidad_id <= 0) {
    echo "<div class='alert alert-danger'>Entidad no válida.</div>";
    return;
}

// Podés cargar datos generales si querés mostrarlos arriba
// Ejemplo básico:
$conn = getConnection();
$sql = "SELECT id, nombre, ruc, direccion FROM entidades WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $entidad_id);
$stmt->execute();
$result = $stmt->get_result();
$entidad = $result->fetch_assoc();

if (!$entidad) {
    echo "<div class='alert alert-warning'>Entidad no encontrada.</div>";
    return;
}

// Incluye la vista visual
include __DIR__ . '/../vista_entidad.php';