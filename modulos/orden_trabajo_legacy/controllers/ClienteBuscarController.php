<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';
$conn = getConnection();

$buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : "";

$sql = "
    SELECT id, nombre 
    FROM clientes 
    WHERE estado = 'activo'
";

if ($buscar !== "") {
    $buscar = $conn->real_escape_string($buscar);
    $sql .= " AND nombre LIKE '%$buscar%' ";
}

$sql .= " ORDER BY nombre ASC LIMIT 20";

$res = $conn->query($sql);

$data = array();
while ($row = $res->fetch_assoc()) {
    $data[] = $row;
}

header("Content-Type: application/json");
echo json_encode($data);

