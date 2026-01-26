<?php
// archivo: /modulos/conductores/acciones/obtener.php
// Devuelve los datos completos de un conductor en formato JSON

if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json; charset=utf-8');

// Validar sesión
if (!isset($_SESSION['usuario'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Sesión no válida'
    ]);
    exit;
}

// Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID inválido'
    ]);
    exit;
}

$id = intval($_GET['id']);

// Conexión a BD
require __DIR__ . '/../../../includes/config.php';
require __DIR__ . '/../../ubigeo/helpers/ubigeo_helper.php'; // ← IMPORTANTE

$conn = getConnection();

if (!$conn || !($conn instanceof mysqli)) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión']);
    exit;
}

// Consulta segura
$sql = "SELECT 
            id,
            nombres,
            apellidos,
            dni,
            telefono,
            licencia_conducir,
            correo,
            direccion,
            departamento_id,
            provincia_id,
            distrito_id,
            activo,
            foto
        FROM conductores
        WHERE id = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Validar existencia
if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Conductor no encontrado'
    ]);
    exit;
}

$data = $result->fetch_assoc();

// Normalizar valores nulos
foreach ($data as $k => $v) {
    if ($v === null) $data[$k] = "";
}

// Construir ruta de foto
if (!empty($data['foto'])) {
    $nombreFoto = basename($data['foto']);
    $rutaFisica = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . "/uploads/conductores/" . $nombreFoto;


    if (is_file($rutaFisica)) {
        $data['foto'] = '/uploads/conductores/' . $nombreFoto;
    } else {
        $data['foto'] = null;
    }
} else {
    $data['foto'] = null;
}

// -------------------------------------------------------------
// UBIGEO: Obtener nombres desde el helper
// -------------------------------------------------------------
$data['departamento_nombre'] = obtenerNombreDepartamento($data['departamento_id']);
$data['provincia_nombre']    = obtenerNombreProvincia($data['provincia_id']);
$data['distrito_nombre']     = obtenerNombreDistrito($data['distrito_id']);

// Respuesta final
echo json_encode([
    'success' => true,
    'data' => $data
]);
