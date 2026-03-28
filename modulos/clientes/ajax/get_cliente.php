<?php
// archivo: /modulos/clientes/ajax/get_cliente.php

header('Content-Type: application/json');

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

$id = intval($_GET['id'] ?? 0);

$sql = "SELECT * FROM clientes WHERE id = $id LIMIT 1";
$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {
    echo json_encode($res->fetch_assoc());
} else {
    echo json_encode(["error" => "Cliente no encontrado"]);
}
exit;
