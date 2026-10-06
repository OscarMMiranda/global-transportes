<?php
// ======================================================
//  CONTROLLER: EstadoListarController.php
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Listar estados corporativos OT
//  GLOBAL 2026 — Arquitectura Limpia (Versión 4.6)
// ======================================================

require_once __DIR__ . '/../../../includes/config.php';

$conn = getConnection();

if (!$conn) {

    error_log("[ERP-OT] Error de conexión en EstadoListarController: " . date('Y-m-d H:i:s'));

    echo json_encode(array(
        "ok"   => false,
        "msg"  => "Error de conexión con la base de datos",
        "data" => array()
    ));
    exit;
}

$sql = "
SELECT 
    id,
    nombre
FROM estado_orden_trabajo
ORDER BY nombre ASC
";

$res = $conn->query($sql);

$estados = array();

if ($res && $res->num_rows > 0) {
    while ($fila = $res->fetch_assoc()) {
        $estados[] = $fila;
    }
}

echo json_encode(array(
    "ok"   => true,
    "msg"  => "Estados obtenidos correctamente",
    "data" => $estados
));

exit;
