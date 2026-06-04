<?php
// archivo: /modulos/vehiculos/acciones/get_documento.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

$id = intval($_GET['id']);

$sql = "SELECT * FROM documentos WHERE id = $id LIMIT 1";
$res = $conn->query($sql);

if (!$res) {
    echo json_encode(["success" => false, "error" => $conn->error]);
    exit;
}

echo json_encode([
    "success" => true,
    "data" => $res->fetch_assoc()
]);
