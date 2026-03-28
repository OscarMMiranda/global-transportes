<?php
// archivo: /modulos/clientes/ajax/ver_cliente.php
header('Content-Type: application/json');

require_once __DIR__ . '/../../../includes/config.php';
$conexion = getConnection();

if (!$conexion) {
    echo json_encode(["error" => "No se pudo conectar a la base de datos"]);
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo json_encode(["error" => "ID inválido"]);
    exit;
}

$sql = "SELECT * FROM clientes WHERE id = $id LIMIT 1";
$result = mysqli_query($conexion, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    echo json_encode(mysqli_fetch_assoc($result));
} else {
    echo json_encode(["error" => "Cliente no encontrado"]);
}

exit;