<?php
// archivo: /modulos/conductores/acciones/nuevo.php

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../controllers/conductores_controller.php';

header('Content-Type: application/json; charset=utf-8');

// Conexión segura
$conn = getConnection();
if (!$conn || !($conn instanceof mysqli)) {
    echo json_encode(['success' => false, 'error' => '❌ Error de conexión a la base de datos']);
    exit;
}

try {
    // Forzar id=0 para que guardarConductor inserte un nuevo registro
    $_POST['id'] = 0;

    // Ejecutar inserción
    $error = guardarConductor($conn, $_POST, $_FILES);

    if ($error === '') {
        // Obtener el último ID insertado
        $nuevoId = $conn->insert_id;

        // Recuperar datos del nuevo conductor
        $conductor = obtenerConductorPorId($conn, $nuevoId);

        if ($conductor && isset($conductor['id'])) {
            // Construir ruta completa de la foto si existe
            $fotoRuta = null;
            if (!empty($conductor['foto'])) {
                $nombreFoto = basename($conductor['foto']); // sanitizar
                $fotoRuta = '/uploads/conductores/' . $nombreFoto;

                // Validar existencia física
                $rutaFisica = __DIR__ . '/../../../uploads/conductores/' . $nombreFoto;
                if (!file_exists($rutaFisica)) {
                    $fotoRuta = null;
                }
            }

            // Normalizar salida
            $data = [
                'id' => isset($conductor['id']) ? (int)$conductor['id'] : 0,
                'nombres' => isset($conductor['nombres']) ? $conductor['nombres'] : '',
                'apellidos' => isset($conductor['apellidos']) ? $conductor['apellidos'] : '',
                'dni' => isset($conductor['dni']) ? $conductor['dni'] : '',
                'licencia_conducir' => isset($conductor['licencia_conducir']) ? $conductor['licencia_conducir'] : '',
                'telefono' => isset($conductor['telefono']) ? $conductor['telefono'] : '',
                'correo' => isset($conductor['correo']) ? $conductor['correo'] : '',
                'direccion' => isset($conductor['direccion']) ? $conductor['direccion'] : '',
                'activo' => isset($conductor['activo']) ? (int)$conductor['activo'] : 0,
                'foto' => $fotoRuta
            ];

            echo json_encode(['success' => true, 'data' => $data]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se pudo recuperar el nuevo conductor']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => $error]);
    }
} catch (Exception $e) {
    error_log('❌ Error en nuevo.php: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Error al crear conductor']);
}