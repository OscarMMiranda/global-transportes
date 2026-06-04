<?php
// archivo: /modulos/vehiculos/acciones/ver_ficha.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

$id = intval($_POST['id']);

$sql = "
    SELECT 
        ft.*,
        v.placa
    FROM vehiculo_ficha_tecnica ft
    LEFT JOIN vehiculos v ON v.id = ft.vehiculo_id
    WHERE ft.vehiculo_id = $id
";

$res = $conn->query($sql);

if (!$res) {
    echo json_encode([
        "success" => false,
        "msg" => "Error en consulta SQL"
    ]);
    exit;
}

$data = $res->fetch_assoc();

function campo($label, $valor) {
    $valor = $valor ? htmlspecialchars($valor) : "—";
    return "
        <div class='row mb-2'>
            <div class='col-md-4 fw-bold'>$label:</div>
            <div class='col-md-8'>$valor</div>
        </div>
    ";
}

$html = "
<div class='container-fluid'>

    <h6 class='fw-bold mt-1'>Motor</h6>
    " . campo("Marca Motor", $data['motor_marca']) . "
    " . campo("Modelo Motor", $data['motor_modelo']) . "
    " . campo("N° Serie Motor", $data['motor_serie']) . "
    " . campo("Cilindrada", $data['cilindrada']) . "
    " . campo("Potencia (HP)", $data['potencia_hp']) . "

    <hr>

    <h6 class='fw-bold mt-1'>Chasis</h6>
    " . campo("Marca Chasis", $data['chasis_marca']) . "
    " . campo("Modelo Chasis", $data['chasis_modelo']) . "
    " . campo("N° Serie Chasis", $data['chasis_serie']) . "

    <hr>

    <h6 class='fw-bold mt-1'>Dimensiones</h6>
    " . campo("Largo (m)", $data['largo']) . "
    " . campo("Ancho (m)", $data['ancho']) . "
    " . campo("Alto (m)", $data['alto']) . "

    <hr>

    <h6 class='fw-bold mt-1'>Capacidades</h6>
    " . campo("Peso Bruto Vehicular (PBV)", $data['pbv']) . "
    " . campo("Carga Útil", $data['carga_util']) . "
    " . campo("N° Ejes", $data['ejes']) . "

</div>
";

echo json_encode([
    "success" => true,
    "html" => $html
]);
