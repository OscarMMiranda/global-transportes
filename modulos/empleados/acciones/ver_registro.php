<?php
// archivo: /modulos/empleados/acciones/ver_registro.php

if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Sesión no válida']);
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

$id = intval($_GET['id']);

require __DIR__ . '/../../../includes/config.php';
require __DIR__ . '/../../ubigeo/helpers/ubigeo_helper.php';

$conn = getConnection();
if (!$conn || !($conn instanceof mysqli)) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión']);
    exit;
}

$sql = "SELECT 
            id,
            nombres,
            apellidos,
            dni,
            telefono,
            correo,
            direccion,
            departamento_id,
            provincia_id,
            distrito_id,
            empresa_id,
            fecha_ingreso,
            estado,
            foto
        FROM empleados
        WHERE id = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Empleado no encontrado']);
    exit;
}

$data = $result->fetch_assoc();

// Normalizar nulos
foreach ($data as $k => $v) {
    if ($v === null) $data[$k] = "";
}

// Foto
if (!empty($data['foto'])) {
    $nombreFoto = basename($data['foto']);
    $rutaFisica = $_SERVER['DOCUMENT_ROOT'] . "/uploads/empleados/" . $nombreFoto;

    if (is_file($rutaFisica)) {
        $data['foto'] = "/uploads/empleados/" . $nombreFoto;
    } else {
        $data['foto'] = null;
    }
} else {
    $data['foto'] = null;
}

// Ubigeo
$data['departamento_nombre'] = obtenerNombreDepartamento($data['departamento_id']);
$data['provincia_nombre']    = obtenerNombreProvincia($data['provincia_id']);
$data['distrito_nombre']     = obtenerNombreDistrito($data['distrito_id']);

// Roles
$roles = [];
$r = $conn->query("
    SELECT r.nombre 
    FROM empleado_rol er
    INNER JOIN roles_empleados r ON r.id = er.rol_id
    WHERE er.empleado_id = $id
");

while ($row = $r->fetch_assoc()) {
    $roles[] = $row['nombre'];
}

$data['roles'] = $roles;

echo json_encode([
    'success' => true,
    'data' => $data
]);
