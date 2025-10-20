<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers/auditoria_lugares.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = getConnection();
if (!($conn instanceof mysqli)) {
    echo json_encode(['estado' => 'error', 'mensaje' => 'Error de conexión']);
    return;
}

// ✅ Captura de usuario y fecha
$usuario = isset($_SESSION['usuario']) ? $conn->real_escape_string($_SESSION['usuario']) : 'sistema';
$ahora   = date('Y-m-d H:i:s');

// ✅ Validar campos obligatorios
$nombre          = isset($_POST['nombre_lugar'])      ? trim($_POST['nombre_lugar'])      : '';
$direccion       = isset($_POST['direccion_lugar'])   ? trim($_POST['direccion_lugar'])   : '';
$tipo_id         = isset($_POST['tipo_id'])           ? intval($_POST['tipo_id'])         : 0;
$distrito_id     = isset($_POST['distrito_id'])       ? intval($_POST['distrito_id'])     : 0;
$provincia_id    = isset($_POST['provincia_id'])      ? intval($_POST['provincia_id'])    : 0;
$departamento_id = isset($_POST['departamento_id'])   ? intval($_POST['departamento_id']) : 0;
$entidad_id      = isset($_POST['entidad_id_lugar'])  ? intval($_POST['entidad_id_lugar']) : 0;
$id              = isset($_POST['id_lugar'])          ? intval($_POST['id_lugar'])        : 0;

$errores = [];
if (!$nombre)          $errores[] = 'nombre_lugar';
if (!$tipo_id)         $errores[] = 'tipo_id';
if (!$distrito_id)     $errores[] = 'distrito_id';
if (!$provincia_id)    $errores[] = 'provincia_id';
if (!$departamento_id) $errores[] = 'departamento_id';
if (!$entidad_id)      $errores[] = 'entidad_id_lugar';

if (count($errores) > 0) {
    echo json_encode(['estado' => 'error', 'mensaje' => 'Faltan campos obligatorios', 'campos' => $errores]);
    return;
}

// ✅ Sanitizar
$direccion = $conn->real_escape_string($direccion);
$nombre    = $conn->real_escape_string($nombre);

// ✅ Insertar o actualizar con trazabilidad
if ($id > 0) {
    $sql = "UPDATE lugares SET
              nombre = '$nombre',
              direccion = '$direccion',
              tipo_id = $tipo_id,
              distrito_id = $distrito_id,
              provincia_id = $provincia_id,
              departamento_id = $departamento_id,
              entidad_id = $entidad_id,
              modificado_por = '$usuario',
              fecha_modificacion = '$ahora'
            WHERE id = $id";
} else {
    $sql = "INSERT INTO lugares (
              nombre, direccion, tipo_id, distrito_id, provincia_id, departamento_id, entidad_id, estado,
              creado_por, fecha_creacion
            ) VALUES (
              '$nombre', '$direccion', $tipo_id, $distrito_id, $provincia_id, $departamento_id, $entidad_id, 'activo',
              '$usuario', '$ahora'
            )";
}

$res = $conn->query($sql);
if (!$res) {
    echo json_encode(['estado' => 'error', 'mensaje' => 'Error SQL: ' . $conn->error]);
    return;
}

// ✅ Auditoría con validación de cambios reales
$valores_despues = [
    'nombre' => $nombre,
    'direccion' => $direccion,
    'tipo_id' => $tipo_id,
    'distrito_id' => $distrito_id,
    'provincia_id' => $provincia_id,
    'departamento_id' => $departamento_id,
    'entidad_id' => $entidad_id
];

if ($id > 0) {
    $resAntes = $conn->query("SELECT nombre, direccion, tipo_id, distrito_id, provincia_id, departamento_id, entidad_id FROM lugares WHERE id = $id");
    $valores_antes = $resAntes ? $resAntes->fetch_assoc() : [];

    if ($valores_antes !== $valores_despues) {
        auditarCambioLugar($conn, $id, 'update', $usuario, $valores_antes, $valores_despues);
    } else {
        error_log("🟡 Sin cambios en lugar #$id — auditoría omitida");
    }

    echo json_encode(['estado' => 'ok', 'id' => $id]);
    return;

} else {
    $nuevo_id = $conn->insert_id;
    auditarCambioLugar($conn, $nuevo_id, 'insert', $usuario, [], $valores_despues);

    echo json_encode(['estado' => 'ok', 'id' => $nuevo_id]);
    return;
}
?>