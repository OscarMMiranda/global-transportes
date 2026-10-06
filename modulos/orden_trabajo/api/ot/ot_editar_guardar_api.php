<?php
// ======================================================
// API: ot_editar_guardar_api.php
// MÓDULO: Órdenes de Trabajo
// RESPONSABILIDAD: Guardar cambios de una OT
// GLOBAL 2026
// PHP 5.6 Compatible
// ======================================================

// ------------------------------------------------------
// SESIÓN
// ------------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {

    echo json_encode(array(
        'ok'  => false,
        'msg' => 'Sesión no válida'
    ));

    exit;
}

// ------------------------------------------------------
// CONFIGURACIÓN
// ------------------------------------------------------
require_once __DIR__ . '/../../../../includes/config.php';

$conn = getConnection();

if (!$conn) {

    echo json_encode(array(
        'ok'  => false,
        'msg' => 'Error de conexión'
    ));

    exit;
}

// ------------------------------------------------------
// VALIDAR MÉTODO
// ------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    echo json_encode(array(
        'ok'  => false,
        'msg' => 'Método no permitido'
    ));

    exit;
}

// ------------------------------------------------------
// DATOS
// ------------------------------------------------------
$id = isset($_POST['id'])
    ? intval($_POST['id'])
    : 0;

if ($id <= 0) {

    echo json_encode(array(
        'ok'  => false,
        'msg' => 'ID inválido'
    ));

    exit;
}

$numero_ot = isset($_POST['numero_ot'])
    ? trim($_POST['numero_ot'])
    : '';

$fecha = isset($_POST['fecha'])
    ? trim($_POST['fecha'])
    : '';

$empresa_id = isset($_POST['empresa_id'])
    ? intval($_POST['empresa_id'])
    : 0;

$cliente_id = isset($_POST['cliente_id'])
    ? intval($_POST['cliente_id'])
    : 0;

$tipo_ot_id = isset($_POST['tipo_ot_id'])
    ? intval($_POST['tipo_ot_id'])
    : 0;

$oc_cliente = isset($_POST['oc_cliente'])
    ? trim($_POST['oc_cliente'])
    : '';

$numero_dam = isset($_POST['numero_dam'])
    ? trim($_POST['numero_dam'])
    : '';

$numero_booking = isset($_POST['numero_booking'])
    ? trim($_POST['numero_booking'])
    : '';

$otros = isset($_POST['otros'])
    ? trim($_POST['otros'])
    : '';

$usuario_id = $_SESSION['usuario_id'];

// ------------------------------------------------------
// SQL UPDATE
// ------------------------------------------------------
$sql = "
    UPDATE ordenes_trabajo
       SET numero_ot          = ?,
           fecha              = ?,
           empresa_id         = ?,
           cliente_id         = ?,
           tipo_ot_id         = ?,
           oc_cliente         = ?,
           numero_dam         = ?,
           numero_booking     = ?,
           otros              = ?,
           modificado_por     = ?,
           fecha_modificacion = NOW()
     WHERE id = ?
";

$stmt = $conn->prepare($sql);

if (!$stmt) {

    echo json_encode(array(
        'ok'  => false,
        'msg' => $conn->error
    ));

    exit;
}

$stmt->bind_param(
    'ssiiissssii',
    $numero_ot,
    $fecha,
    $empresa_id,
    $cliente_id,
    $tipo_ot_id,
    $oc_cliente,
    $numero_dam,
    $numero_booking,
    $otros,
    $usuario_id,
    $id
);

if (!$stmt->execute()) {

    echo json_encode(array(
        'ok'  => false,
        'msg' => $stmt->error
    ));

    exit;
}

$stmt->close();

// ------------------------------------------------------
// RESPUESTA
// ------------------------------------------------------
echo json_encode(array(
    'ok'  => true,
    'msg' => 'OT actualizada correctamente'
));

exit;