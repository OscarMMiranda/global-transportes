<?php
// archivo: /modulos/mantenimiento/tipo_vehiculo/acciones/categorias.php
header('Content-Type: application/json');

require_once __DIR__ . '/../../../../includes/conexion.php';

$conn = getConnection();

$sql = "SELECT id, nombre FROM categoria_vehiculo ORDER BY nombre ASC";
$res = $conn->query($sql);

$data = array();

if ($res) {
    while ($row = $res->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode(array(
    "ok" => true,
    "data" => $data
));
exit;
