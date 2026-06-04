<?php
    //  archivo: modulos/vehiculos/acciones/ver_basico.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

$id = intval($_POST['id']);

$sql = "
    SELECT v.*, 
           m.nombre AS marca,
           e.nombre AS estado,
           t.nombre AS tipo,
           c.nombre AS configuracion,
           emp.razon_social AS empresa
    FROM vehiculos v
    LEFT JOIN marca_vehiculo m ON v.marca_id = m.id
    LEFT JOIN estado_vehiculo e ON v.estado_id = e.id
    LEFT JOIN tipo_vehiculo t ON v.tipo_id = t.id
    LEFT JOIN configuracion_vehiculo c ON v.configuracion_id = c.id
    LEFT JOIN empresa emp ON v.empresa_id = emp.id
    WHERE v.id = $id
";

$res = $conn->query($sql);
$data = $res->fetch_assoc();

function campo($label, $valor) {
    $valor = $valor ? htmlspecialchars($valor) : "—";
    return "
        <div class='row mb-1'>
            <div class='col-md-4 fw-bold'>$label:</div>
            <div class='col-md-8'>$valor</div>
        </div>
    ";
}

$html = "
    <div class='container-fluid'>

        " . campo("Placa", $data['placa']) . "
        " . campo("Marca", $data['marca']) . "
        " . campo("Modelo", $data['modelo']) . "
        " . campo("Año", $data['anio']) . "
        " . campo("Tipo", $data['tipo']) . "
        " . campo("Estado", $data['estado']) . "
        " . campo("Configuración", $data['configuracion']) . "
        " . campo("Empresa", $data['empresa']) . "

        <hr>

        <h6 class='fw-bold mb-3'>Observaciones</h6>
        <div class='border rounded p-2' style='min-height:60px;'>
            " . ($data['observaciones'] ? nl2br(htmlspecialchars($data['observaciones'])) : "—") . "
        </div>

    </div>
";

echo json_encode([
    "success" => true,
    "html" => $html
]);
