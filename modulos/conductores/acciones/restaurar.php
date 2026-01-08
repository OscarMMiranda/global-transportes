<?php
// archivo: /modulos/conductores/acciones/restaurar.php
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
    // Restaurar: marcar como activo nuevamente
    $stmt = $conn->prepare("UPDATE conductores SET activo = 1 WHERE id = ?");
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => $conn->error]);
        exit;
    }

    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
} catch (Exception $e) {
    error_log("❌ restaurar.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}