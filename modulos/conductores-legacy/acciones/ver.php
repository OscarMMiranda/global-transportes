<?php
// archivo: /modulos/conductores/acciones/ver.php

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../controllers/conductores_controller.php';

header('Content-Type: application/json; charset=utf-8');

$conn = getConnection();
if (!$conn) {
    echo json_encode(['success' => false, 'error' => 'Conexión fallida']);
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : 0);
if ($id <= 0) {
    echo json_encode(['success' => false, 'error' => 'ID inválido']);
    exit;
}

try {
    $conductor = obtenerConductorPorId($conn, $id);

    if ($conductor && isset($conductor['id'])) {
        // Sanitizar y construir URL pública de la foto
        $fotoRuta = null;
        if (!empty($conductor['foto'])) {
            // Guarda en BD solo el nombre: ej. "conductor_10216602_1751683810.png"
            $nombreFoto = basename($conductor['foto']); // evita traversal y subrutas
            $fotoRuta = '/uploads/conductores/' . $nombreFoto;

            // Opcional: validar existencia física; si no existe, dejar null
            $rutaFisica = __DIR__ . '/../../../uploads/conductores/' . $nombreFoto;
            if (!is_file($rutaFisica)) {
                $fotoRuta = null;
            }
        }

        echo json_encode([
            'success' => true,
            'data' => [
                'id' => $conductor['id'],
                'nombres' => $conductor['nombres'],
                'apellidos' => $conductor['apellidos'],
                'dni' => $conductor['dni'],
                'licencia_conducir' => $conductor['licencia_conducir'],
                'telefono' => $conductor['telefono'],
                'correo' => $conductor['correo'],
                'direccion' => $conductor['direccion'],
                'activo' => $conductor['activo'],
                'foto' => $fotoRuta
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Conductor no encontrado']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}