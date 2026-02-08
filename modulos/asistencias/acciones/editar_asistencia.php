<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

/* RUTAS CORRECTAS */
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/funciones_historial.php';

$conn = getConnection();

if (!$conn) {
    echo json_encode([
        'ok' => false,
        'error' => 'No hay conexión con la base de datos'
    ]);
    exit;
}

$id      = isset($_POST['id']) ? intval($_POST['id']) : 0;
$tipo    = isset($_POST['tipo']) ? intval($_POST['tipo']) : 0;
$entrada = isset($_POST['entrada']) && $_POST['entrada'] !== "" ? $_POST['entrada'] : null;
$salida  = isset($_POST['salida']) && $_POST['salida'] !== "" ? $_POST['salida'] : null;
$obs     = isset($_POST['obs']) ? trim($_POST['obs']) : "";

/* Normalizar TIME */
function normalizarHora($h) {
    if ($h === null) return null;
    if (preg_match('/^\d{2}:\d{2}$/', $h)) return $h . ':00';
    if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $h)) return $h;
    return null;
}

$entrada = normalizarHora($entrada);
$salida  = normalizarHora($salida);

if ($id <= 0) {
    echo json_encode(['ok' => false, 'error' => 'ID inválido']);
    exit;
}

$sql = "
    UPDATE asistencia_conductores
    SET 
        tipo_id      = ?,
        hora_entrada = ?,
        hora_salida  = ?,
        observacion  = ?,
        updated_at   = NOW()
    WHERE id = ?
";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo json_encode([
        'ok' => false,
        'error' => 'Error en prepare(): ' . mysqli_error($conn)
    ]);
    exit;
}

mysqli_stmt_bind_param($stmt, "isssi", $tipo, $entrada, $salida, $obs, $id);

if (mysqli_stmt_execute($stmt)) {

    $usuario = $_SESSION['usuario'] ?? 'Sistema';
    registrarHistorial($id, 'editar', $usuario, 'Asistencia modificada');

    echo json_encode(['ok' => true]);

} else {
    echo json_encode([
        'ok'    => false,
        'error' => mysqli_error($conn)
    ]);
}
