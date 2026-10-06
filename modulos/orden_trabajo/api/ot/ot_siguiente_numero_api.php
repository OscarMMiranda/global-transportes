<?php
// ======================================================
//  API: ot_siguiente_numero_api.php
//  RESPONSABILIDAD: Obtener el siguiente número de OT del año
//  GLOBAL 2026 — Arquitectura Limpia (Módulo Crear OT)
// ======================================================

// require_once __DIR__ . '/../../../includes/config.php';

require_once __DIR__ . '/../../../../includes/config.php';
$conn = getConnection();

// Año recibido o año actual
$anio = isset($_POST['anio']) ? intval($_POST['anio']) : intval(date('Y'));

// ------------------------------------------------------
// 1. Buscar el último numero_ot del año
// ------------------------------------------------------
$sql = "
    SELECT numero_ot
    FROM ordenes_trabajo
    WHERE numero_ot LIKE '%-$anio'
    ORDER BY numero_ot DESC
    LIMIT 1
";

$res = $conn->query($sql);

// ------------------------------------------------------
// 2. Si existe una OT del año → incrementar correlativo
// ------------------------------------------------------
if ($res && $res->num_rows > 0) {

    $row = $res->fetch_assoc();
    $numero_ot = $row['numero_ot'];   // Ej: "0013-2026"

    // Limpieza por si vienen espacios
    $numero_ot = str_replace(' ', '', $numero_ot);

    list($correlativo, $year) = explode('-', $numero_ot);

    $correlativo = intval($correlativo) + 1;

    // Formato 4 dígitos
    $nuevo_correlativo = str_pad($correlativo, 4, '0', STR_PAD_LEFT);

    $siguiente = $nuevo_correlativo . '-' . $anio;

    echo json_encode([
        "ok" => true,
        "siguiente_ot" => $siguiente
    ]);
    exit;
}

// ------------------------------------------------------
// 3. Si NO existe ninguna OT del año → empezar en 0001
// ------------------------------------------------------
echo json_encode([
    "ok" => true,
    "siguiente_ot" => '0001-' . $anio
]);
exit;
