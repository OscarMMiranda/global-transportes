<?php
// archivo: /modulos/conductores/acciones/borrar.php
require_once __DIR__ . '/../../../includes/config.php';

header('Content-Type: application/json; charset=utf-8');

$conn = getConnection();
if (!$conn) {
    echo json_encode(['success' => false, 'error' => 'Conexión fallida']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    echo json_encode(['success' => false, 'error' => 'ID inválido']);
    exit;
}

try {
    // Soft delete: marcar como inactivo
    $stmt = $conn->prepare("UPDATE conductores SET activo = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
} catch (Exception $e) {
    error_log("❌ borrar.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}