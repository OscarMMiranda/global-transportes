<?php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

$ot_id = intval($_POST['orden_trabajo_id']);
$fecha = $_POST['fecha_viaje'];
$semana = $_POST['semana_viaje'];

if($ot_id <= 0){
    echo json_encode(["ok"=>false, "msg"=>"OT inválida"]);
    exit;
}

$sql = "
INSERT INTO ot_viajes (orden_trabajo_id, fecha_viaje, semana_viaje, created_at)
VALUES ($ot_id, '$fecha', '$semana', NOW())
";

if(!$conn->query($sql)){
    echo json_encode(["ok"=>false, "msg"=>"Error al guardar"]);
    exit;
}

echo json_encode(["ok"=>true]);
exit;
