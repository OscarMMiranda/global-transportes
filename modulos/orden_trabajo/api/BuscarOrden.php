<?php
    //  archivo :   /modulos/orden_trabajo/api/BuscarOrden.php

session_start();
require_once '../../includes/conexion.php';

// Activar depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    echo json_encode(["success" => false, "message" => "❌ Acceso denegado."]);
    exit;
}

// Verificar que se recibió el número OT
if (!isset($_GET['numero_ot']) || empty($_GET['numero_ot'])) {
    echo json_encode(["success" => false, "message" => "❌ Número OT no proporcionado."]);
    exit;
}

$numero_ot = trim($_GET['numero_ot']);

// Consultar la orden en la base de datos
$sqlOrden = "SELECT id, numero_ot, fecha, cliente_id, tipo_ot_id, empresa_id 
             FROM ordenes_trabajo WHERE numero_ot = ?";
$stmtOrden = $conn->prepare($sqlOrden);
$stmtOrden->bind_param("s", $numero_ot);
$stmtOrden->execute();
$resultOrden = $stmtOrden->get_result();

if ($resultOrden->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "❌ Orden no encontrada."]);
    exit;
}

$orden = $resultOrden->fetch_assoc();
$stmtOrden->close();

// Enviar respuesta en formato JSON
echo json_encode([
    "success" => true,
    "id" => $orden['id'],
    "numero_ot" => $orden['numero_ot'],
    "fecha" => $orden['fecha'],
    "cliente_id" => $orden['cliente_id'],
    "tipo_ot_id" => $orden['tipo_ot_id'],
    "empresa_id" => $orden['empresa_id']
]);

exit;
?>
