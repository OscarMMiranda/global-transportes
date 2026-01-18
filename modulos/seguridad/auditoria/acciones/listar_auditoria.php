<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once __DIR__ . '/../../../../includes/config.php';

$conn = getConnection();

if (!$conn) {
    echo json_encode(array("error" => "No hay conexión"));
    exit;
}

// PHP 5.6
$usuario = isset($_POST['usuario_id']) ? $_POST['usuario_id'] : '';
$modulo  = isset($_POST['modulo'])     ? $_POST['modulo']     : '';
$accion  = isset($_POST['accion'])     ? $_POST['accion']     : '';
$desde   = isset($_POST['desde'])      ? $_POST['desde']      : '';
$hasta   = isset($_POST['hasta'])      ? $_POST['hasta']      : '';

$where = " WHERE 1=1 ";

if ($usuario !== '') $where .= " AND a.usuario_id = " . intval($usuario);
if ($modulo !== '')  $where .= " AND a.modulo = '" . $conn->real_escape_string($modulo) . "'";
if ($accion !== '')  $where .= " AND a.accion = '" . $conn->real_escape_string($accion) . "'";
if ($desde !== '')   $where .= " AND DATE(a.fecha) >= '" . $conn->real_escape_string($desde) . "'";
if ($hasta !== '')   $where .= " AND DATE(a.fecha) <= '" . $conn->real_escape_string($hasta) . "'";

$sql = "
SELECT 
    a.id,
    a.fecha,
    CONCAT(u.nombre, ' ', u.apellido) AS usuario,
    a.modulo,
    a.accion,
    a.registro_id,
    a.descripcion,
    a.ip
FROM auditoria a
LEFT JOIN usuarios u ON u.id = a.usuario_id
$where
ORDER BY a.id DESC
";

$res = $conn->query($sql);

if (!$res) {
    echo json_encode(array(
        "error" => $conn->error,
        "sql"   => $sql
    ));
    exit;
}

$data = array();
while ($row = $res->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(array("data" => $data));