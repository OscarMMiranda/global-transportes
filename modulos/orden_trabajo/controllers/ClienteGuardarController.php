<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';
$conn = getConnection();

$nombre    = trim($_POST['nombre']);
$ruc       = trim($_POST['ruc']);
$direccion = trim($_POST['direccion']);

$sql = "
    INSERT INTO clientes (nombre, ruc, direccion, estado)
    VALUES ('$nombre', '$ruc', '$direccion', 'activo')
";

if ($conn->query($sql)) {
    echo json_encode([
        "estado" => "ok",
        "id"     => $conn->insert_id,
        "nombre" => $nombre
    ]);
} else {
    echo json_encode([
        "estado"  => "error",
        "mensaje" => "No se pudo registrar el cliente"
    ]);
}
