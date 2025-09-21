<?php
// archivo: crear.php — inserción segura de entidad por AJAX

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

if (!is_object($conn) || get_class($conn) !== 'mysqli') {
    echo "Error de conexión";
    return;
}

// Capturar y validar datos
$nombre          = isset($_POST['nombre'])          ? trim($_POST['nombre'])          : '';
$ruc             = isset($_POST['ruc'])             ? trim($_POST['ruc'])             : null;
$direccion       = isset($_POST['direccion'])       ? trim($_POST['direccion'])       : null;
$tipo_id         = isset($_POST['tipo_id'])         ? intval($_POST['tipo_id'])       : 0;
$departamento_id = isset($_POST['departamento_id']) ? intval($_POST['departamento_id']) : 0;
$provincia_id    = isset($_POST['provincia_id'])    ? intval($_POST['provincia_id'])  : 0;
$distrito_id     = isset($_POST['distrito_id'])     ? intval($_POST['distrito_id'])   : 0;

// Validación mínima
if ($nombre === '' || $tipo_id <= 0 || $departamento_id <= 0 || $provincia_id <= 0 || $distrito_id <= 0) {
    echo "Faltan campos obligatorios";
    return;
}

// Validar existencia de claves foráneas
function validarExistencia($conn, $tabla, $id) {
    $id = intval($id);
    $sql = "SELECT id FROM $tabla WHERE id = $id LIMIT 1";
    $res = $conn->query($sql);
    return ($res && $res->num_rows > 0);
}

if (!validarExistencia($conn, 'tipo_lugares', $tipo_id)) {
    echo "Tipo de entidad inválido";
    return;
}
if (!validarExistencia($conn, 'departamentos', $departamento_id)) {
    echo "Departamento inválido";
    return;
}
if (!validarExistencia($conn, 'provincias', $provincia_id)) {
    echo "Provincia inválida";
    return;
}
if (!validarExistencia($conn, 'distritos', $distrito_id)) {
    echo "Distrito inválido";
    return;
}

// Registrar usuario si está logueado
$creado_por = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : null;

// Preparar inserción
$stmt = $conn->prepare("INSERT INTO entidades (
    nombre, ruc, direccion, tipo_id, departamento_id, provincia_id, distrito_id,
    creado_por, estado, fecha_creacion
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'activo', NOW())");

if (!$stmt) {
    echo "Error al preparar consulta";
    return;
}

$stmt->bind_param("sssiiiiii", $nombre, $ruc, $direccion, $tipo_id, $departamento_id, $provincia_id, $distrito_id, $creado_por);

if ($stmt->execute()) {
    echo "ok";
} else {
    echo "Error al insertar: " . $stmt->error;
}

$stmt->close();