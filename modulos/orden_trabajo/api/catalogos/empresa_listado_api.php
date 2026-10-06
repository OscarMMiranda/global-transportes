<?php
// ======================================================
//  API: empresa_listado_api.php
//  RESPONSABILIDAD: Listado de Empresas
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
    SELECT id, razon_social
    FROM empresa
    ORDER BY razon_social ASC
";

$res = $conn->query($sql);

$data = [];

if ($res && $res->num_rows > 0) {

    while ($row = $res->fetch_assoc()) {
        $data[] = [
            "id"           => intval($row['id']),
            "razon_social" => $row['razon_social']
        ];
    }
}

echo json_encode([
    "ok"   => true,
    "msg"  => "Listado obtenido",
    "data" => $data
]);
exit;
