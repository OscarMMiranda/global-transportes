<?php
// ======================================================
//  CONTROLLER: GetNextOTController.php
//  RESPONSABILIDAD: Obtener el siguiente número de OT del año
//  GLOBAL 2026 — Arquitectura Limpia (Versión 4.8)
// ======================================================

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

// ---------------------------------------------
// 1. Obtener fecha enviada o usar fecha actual
// ---------------------------------------------
$fecha = isset($_POST['fecha']) ? trim($_POST['fecha']) : date('Y-m-d');

if (!$fecha) {
    $fecha = date('Y-m-d');
}

$year = date('Y', strtotime($fecha));

// ---------------------------------------------
// 2. Consulta segura para obtener última OT del año
// ---------------------------------------------
$sql = "
    SELECT numero_ot
    FROM ordenes_trabajo
    WHERE YEAR(fecha) = ?
    ORDER BY id DESC
    LIMIT 1
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "ok" => false,
        "msg" => "Error al preparar consulta"
    ]);
    exit;
}

$stmt->bind_param("i", $year);
$stmt->execute();
$res = $stmt->get_result();

// ---------------------------------------------
// 3. Calcular siguiente correlativo
// ---------------------------------------------
if ($res && $res->num_rows > 0) {

    $row = $res->fetch_assoc();
    $numero_ot = trim($row['numero_ot']);   // Ej: "0013-2026"

    list($correlativo, $yr) = explode('-', $numero_ot);

    $nuevo_num = intval($correlativo) + 1;

} else {
    $nuevo_num = 1;
}

// ---------------------------------------------
// 4. Formatear número OT
// ---------------------------------------------
$numero_formateado = str_pad($nuevo_num, 4, '0', STR_PAD_LEFT);
$numero_ot_final   = $numero_formateado . '-' . $year;

// ---------------------------------------------
// 5. Respuesta final
// ---------------------------------------------
echo json_encode([
    "ok"        => true,
    "numero_ot" => $numero_ot_final,
    "year"      => $year
]);
exit;

