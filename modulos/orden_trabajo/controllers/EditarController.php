<?php
// archivo: /modulos/orden_trabajo/controllers/EditarController.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

/* ============================================================
   OBTENER OT COMPLETA
   ============================================================ */
$sql = "
SELECT 
    ot.*,
    c.nombre AS cliente_nombre,
    e.razon_social AS empresa_nombre,
    t.nombre AS tipo_ot_nombre,
    est.nombre AS estado_nombre
FROM ordenes_trabajo ot
LEFT JOIN clientes c ON c.id = ot.cliente_id
LEFT JOIN empresa e ON e.id = ot.empresa_id
LEFT JOIN tipo_ot t ON t.id = ot.tipo_ot_id
LEFT JOIN estado_orden_trabajo est ON est.id = ot.estado_id
WHERE ot.id = $id
LIMIT 1
";

$res = $conn->query($sql);
if (!$res || $res->num_rows === 0) {
    echo json_encode(["ok" => false, "msg" => "No se encontró la OT"]);
    exit;
}

$data = $res->fetch_assoc();

/* ============================================================
   FORMATEAR SEMANA CORPORATIVA
   ============================================================ */
$semana_num = intval($data["semana_ot"]);
$anio = substr($data["fecha"], 0, 4);
$data["semana_formateada"] = "S" . str_pad($semana_num, 2, "0", STR_PAD_LEFT) . "-" . $anio;

/* ============================================================
   CATÁLOGOS EDITABLES (FILTRADOS)
   ============================================================ */

/* --- CLIENTES SOLO ACTIVOS Y NO ELIMINADOS --- */
$sqlClientes = "
SELECT id, nombre
FROM clientes
WHERE estado = 'Activo'
AND deleted_at IS NULL
ORDER BY nombre ASC
";

$resClientes = $conn->query($sqlClientes);
$clientes = array();
if ($resClientes && $resClientes->num_rows > 0) {
    while ($c = $resClientes->fetch_assoc()) {
        $clientes[] = $c;
    }
}

/* --- EMPRESAS --- */
$sqlEmpresas = "
SELECT id, razon_social AS nombre
FROM empresa
ORDER BY razon_social ASC
";

$resEmpresas = $conn->query($sqlEmpresas);
$empresas = array();
if ($resEmpresas && $resEmpresas->num_rows > 0) {
    while ($e = $resEmpresas->fetch_assoc()) {
        $empresas[] = $e;
    }
}

/* --- TIPOS OT --- */
$sqlTipos = "
SELECT id, nombre
FROM tipo_ot
ORDER BY nombre ASC
";

$resTipos = $conn->query($sqlTipos);
$tipos_ot = array();
if ($resTipos && $resTipos->num_rows > 0) {
    while ($t = $resTipos->fetch_assoc()) {
        $tipos_ot[] = $t;
    }
}

/* ============================================================
   RESPUESTA JSON
   ============================================================ */
echo json_encode([
    "ok" => true,
    "data" => $data,
    "clientes" => $clientes,
    "empresas" => $empresas,
    "tipos_ot" => $tipos_ot
]);
