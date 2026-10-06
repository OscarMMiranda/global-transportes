<?php
// ======================================================
//  API: tipo_ot_listado_api.php
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Listado de Tipos de OT
//  GLOBAL 2026 — Arquitectura Limpia
// ======================================================

// require_once __DIR__ . '/../../../includes/config.php';

require_once __DIR__ . '/../../../../includes/config.php';

$conn = getConnection();
if (!$conn) {
    echo json_encode(["ok" => false, "msg" => "Error de conexión", "data" => []]);
    exit;
}

$sql = "
    SELECT id, nombre
    FROM tipo_ot
    WHERE deleted_at IS NULL
    ORDER BY nombre ASC
";

$res = $conn->query($sql);

$data = [];

if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $data[] = [
            "id"     => intval($row['id']),
            "nombre" => $row['nombre']
        ];
    }
}

echo json_encode([
    "ok"   => true,
    "msg"  => "Listado obtenido",
    "data" => $data
]);
exit;
