<?php
// archivo: /modulos/conductores/acciones/ver.php

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../ubigeo/helpers/ubigeo_helper.php'; // ← IMPORTANTE

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

    $sql = "
        SELECT 
            id,
            nombres,
            apellidos,
            dni,
            licencia_conducir,
            telefono,
            correo,
            direccion,
            departamento_id,
            provincia_id,
            distrito_id,
            activo,
            foto
        FROM conductores
        WHERE id = ?
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => $conn->error]);
        exit;
    }

    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
        exit;
    }

    $stmt->bind_result(
        $id_c,
        $nombres,
        $apellidos,
        $dni,
        $licencia,
        $telefono,
        $correo,
        $direccion,
        $departamento_id,
        $provincia_id,
        $distrito_id,
        $activo,
        $foto
    );

    if ($stmt->fetch()) {

        // Foto
        if (!empty($foto)) {
            if (strpos($foto, '/uploads/conductores/') === 0) {
                $fotoFinal = $foto;
            } else {
                $fotoFinal = '/uploads/conductores/' . $foto;
            }
        } else {
            $fotoFinal = null;
        }

        // Obtener nombres de Ubigeo
        $departamento_nombre = obtenerNombreDepartamento($departamento_id);
        $provincia_nombre    = obtenerNombreProvincia($provincia_id);
        $distrito_nombre     = obtenerNombreDistrito($distrito_id);

        $data = [
            'id'                => $id_c,
            'nombres'           => $nombres,
            'apellidos'         => $apellidos,
            'dni'               => $dni,
            'licencia_conducir' => $licencia,
            'telefono'          => $telefono,
            'correo'            => $correo,
            'direccion'         => $direccion,

            // Ubigeo IDs
            'departamento_id'   => $departamento_id,
            'provincia_id'      => $provincia_id,
            'distrito_id'       => $distrito_id,

            // Ubigeo nombres
            'departamento_nombre' => $departamento_nombre,
            'provincia_nombre'    => $provincia_nombre,
            'distrito_nombre'     => $distrito_nombre,

            'activo'            => (int)$activo,
            'foto'              => $fotoFinal
        ];

        echo json_encode(['success' => true, 'data' => $data]);

    } else {
        echo json_encode(['success' => false, 'error' => 'Conductor no encontrado']);
    }

    $stmt->close();

} catch (Exception $e) {
    error_log("❌ ver.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
