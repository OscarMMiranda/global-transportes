<?php
require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

// PHP 5.6 compatible
$fecha = isset($_POST['fecha']) ? $_POST['fecha'] : date('Y-m-d');
$year  = date('Y', strtotime($fecha));

$sql = "
    SELECT numero_ot
    FROM ordenes_trabajo
    WHERE YEAR(fecha) = $year
    ORDER BY id DESC
    LIMIT 1
";

$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {
    $row = $res->fetch_assoc();
    list($num, $yr) = explode('-', $row['numero_ot']);
    $nuevo_num = intval($num) + 1;
} else {
    $nuevo_num = 1;
}

$numero_formateado = str_pad($nuevo_num, 4, '0', STR_PAD_LEFT);
$numero_ot = $numero_formateado . '-' . $year;

echo json_encode([
    "ok" => true,
    "numero_ot" => $numero_ot,
    "year" => $year
]);
exit;
