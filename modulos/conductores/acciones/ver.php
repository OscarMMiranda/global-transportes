<?php
// archivo: /modulos/conductores/acciones/ver.php
require_once __DIR__ . '/../../../includes/config.php';

header('Content-Type: application/json; charset=utf-8');

$conn = getConnection();
if (!$conn) {
    echo json_encode(['success' => false, 'error' => 'Conexión fallida']);
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo json_encode(['success' => false, 'error' => 'ID inválido']);
    exit;
}

try {
    $stmt = $conn->prepare("
        SELECT 
            id,
            nombres,
            apellidos,
            dni,
            licencia_conducir,
            telefono,
            correo,
            direccion,
            activo,
            foto
        FROM conductores
        WHERE id = ?
    ");
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => $conn->error]);
        exit;
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        // Ajustar ruta de la foto si existe
        if (!empty($data['foto'])) {
            // Si en BD ya está guardada la ruta completa (/uploads/conductores/archivo.jpg), no concatenar nada
            if (strpos($data['foto'], '/uploads/conductores/') === 0) {
                $data['foto'] = $data['foto'];
            } else {
                // Si en BD solo está el nombre del archivo, armar la ruta completa
                $data['foto'] = '/uploads/conductores/' . $data['foto'];
            }
        } else {
            $data['foto'] = null; // explícito para que el frontend muestre "Sin foto disponible"
        }

        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Conductor no encontrado']);
    }

    $stmt->close();
} catch (Exception $e) {
    error_log("❌ ver.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}