<?php
// ============================================================
// CONTROLADOR: GetDatosOTController.php
// RESPONSABILIDAD: Obtener datos mínimos de la OT para logística
// ARQUITECTURA LIMPIA 2026 — PHP 5.6 COMPATIBLE
// ============================================================



require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

if(!isset($_POST['orden_trabajo_id'])){
    echo json_encode(["ok"=>false, "msg"=>"OT no recibida"]);
    exit;
}

$ot_id = intval($_POST['orden_trabajo_id']);

$sql = "SELECT fecha, semana_ot FROM ordenes_trabajo WHERE id = $ot_id LIMIT 1";
$res = $conn->query($sql);

if(!$res){
    echo json_encode(["ok"=>false, "msg"=>"Error consultando OT"]);
    exit;
}

if($res->num_rows === 0){
    echo json_encode(["ok"=>false, "msg"=>"OT no encontrada"]);
    exit;
}

$row = $res->fetch_assoc();

echo json_encode([
    "ok" => true,
    "fecha_ot" => $row["fecha"],
    "semana_ot" => $row["semana_ot"]
]);
exit;
