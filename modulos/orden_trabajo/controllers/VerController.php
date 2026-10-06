<?php
// archivo: /modulos/orden_trabajo/controllers/VerController.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

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
LEFT JOIN estado_orden_trabajo est ON est.id = ot.estado_id   -- ✔ CORRECTO
WHERE ot.id = $id
LIMIT 1
";

$res = $conn->query($sql);

if (!$res || $res->num_rows === 0) {
    echo "<div class='alert alert-danger'>No se encontró la OT.</div>";
    exit;
}

$data = $res->fetch_assoc();

/* ============================
   FORMATEAR SEMANA
   ============================ */
$semana_raw = $data["semana_ot"];       // ej: 2024-W38
$anio = substr($semana_raw, 0, 4);      // 2024
$semana_num = substr($semana_raw, 6, 2); // 38

$data["semana_formateada"] = "S" . $semana_num . "-" . $anio;

/* ============================
   CARGAR VISTA
   ============================ */
include __DIR__ . '/../views/ver_ot.php';
