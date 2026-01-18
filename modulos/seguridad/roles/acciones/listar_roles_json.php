<?php
// archivo: /modulos/seguridad/roles/acciones/listar_roles.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';

$conn = getConnection();

$sql = "SELECT id, nombre FROM roles ORDER BY nombre ASC";
$res = $conn->query($sql);

$roles = [];

while ($row = $res->fetch_assoc()) {
    $roles[] = [
        "id" => (int)$row["id"],
        "nombre" => $row["nombre"]
    ];
}

echo json_encode([
    "ok" => true,
    "data" => $roles
]);