<?php
// archivo: /modulos/orden_trabajo/controllers/CreateController.php

header('Content-Type: application/json');

// ===============================
// 🔧 Conexión
// ===============================
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';
$conn = getConnection();

// ===============================
// 🔵 VALIDAR MÉTODO
// ===============================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(array("estado" => "error", "mensaje" => "Método no permitido"));
    exit;
}

// ===============================
// 🔵 VALIDAR CAMPOS OBLIGATORIOS
// ===============================
$campos = array('numero_ot', 'fecha', 'cliente_id', 'tipo_ot_id', 'empresa_id');

foreach ($campos as $campo) {
    if (!isset($_POST[$campo]) || trim($_POST[$campo]) === '') {
        echo json_encode(array("estado" => "error", "mensaje" => "Falta el campo: " . $campo));
        exit;
    }
}

// ===============================
// 🔵 SANITIZAR DATOS
// ===============================
$numero_ot   = mysqli_real_escape_string($conn, trim($_POST['numero_ot']));
$fecha       = mysqli_real_escape_string($conn, trim($_POST['fecha']));
$cliente_id  = intval($_POST['cliente_id']);
$tipo_ot_id  = intval($_POST['tipo_ot_id']);
$empresa_id  = intval($_POST['empresa_id']);
$oc_cliente  = isset($_POST['oc_cliente']) ? mysqli_real_escape_string($conn, trim($_POST['oc_cliente'])) : '';

// ===============================
// 🔵 CALCULAR SEMANA OT
// ===============================
$semana_ot = date('W', strtotime($fecha));

// ===============================
// 🔵 VALIDAR FORMATO DE NUMERO OT
// ===============================
if (!preg_match('/^[0-9]{4}-[0-9]{2}$/', $numero_ot)) {
    echo json_encode(array("estado" => "error", "mensaje" => "Formato de número OT inválido"));
    exit;
}

// ===============================
// 🔵 VALIDAR QUE NO EXISTA DUPLICADO
// ===============================
$sqlCheck = "
    SELECT id 
    FROM ordenes_trabajo 
    WHERE numero_ot = '$numero_ot'
    LIMIT 1
";

$resCheck = $conn->query($sqlCheck);

if ($resCheck && $resCheck->num_rows > 0) {
    echo json_encode(array("estado" => "error", "mensaje" => "El número de OT ya existe"));
    exit;
}

// ===============================
// 🔵 INSERTAR OT (CON ESTADO_OT = 1)
// ===============================
$sqlInsert = "
    INSERT INTO ordenes_trabajo 
    (numero_ot, fecha, cliente_id, empresa_id, tipo_ot_id, oc_cliente, semana_ot, estado_ot)
    VALUES 
    ('$numero_ot', '$fecha', $cliente_id, $empresa_id, $tipo_ot_id, '$oc_cliente', $semana_ot, 1)
";

if ($conn->query($sqlInsert)) {
    echo json_encode(array("estado" => "ok", "mensaje" => "Orden creada correctamente"));
    exit;
} else {
    echo json_encode(array("estado" => "error", "mensaje" => "Error al guardar: " . $conn->error));
    exit;
}
