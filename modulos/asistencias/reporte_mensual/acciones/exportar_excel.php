<?php
// archivo: exportar_excel.php

require_once __DIR__ . '/../../../../includes/config.php';
$conn = getConnection();

// ============================================================
// 1. FILTROS
// ============================================================
$empresa_id_raw   = isset($_GET['empresa_id']) ? $_GET['empresa_id'] : "";
$conductor_id_raw = isset($_GET['conductor_id']) ? $_GET['conductor_id'] : "";
$mes              = isset($_GET['mes']) ? (int)$_GET['mes'] : 0;
$anio             = isset($_GET['anio']) ? (int)$_GET['anio'] : 0;

// empresa
if ($empresa_id_raw === "" || $empresa_id_raw === "ALL") {
    $empresa_id = 0;
} else {
    $empresa_id = (int)$empresa_id_raw;
    if ($empresa_id <= 0) $empresa_id = 0;
}

// conductor
if ($conductor_id_raw === "" || $conductor_id_raw === "ALL") {
    $conductor_id = 0;
} else {
    $conductor_id = (int)$conductor_id_raw;
    if ($conductor_id <= 0) $conductor_id = 0;
}

// ============================================================
// 2. CONSULTA
// ============================================================
$sql = "
    SELECT 
        ac.fecha,
        ac.hora_entrada,
        ac.hora_salida,
        ac.tipo_codigo,
        CONCAT(c.nombres, ' ', c.apellidos) AS conductor,
        e.razon_social AS empresa
    FROM asistencia_conductores ac
    INNER JOIN conductores c ON c.id = ac.conductor_id
    INNER JOIN empresa e ON e.id = c.empresa_id
    WHERE MONTH(ac.fecha) = ?
      AND YEAR(ac.fecha) = ?
";

$params = [$mes, $anio];
$types  = "ii";

if ($empresa_id > 0) {
    $sql .= " AND c.empresa_id = ? ";
    $params[] = $empresa_id;
    $types   .= "i";
}

if ($conductor_id > 0) {
    $sql .= " AND ac.conductor_id = ? ";
    $params[] = $conductor_id;
    $types   .= "i";
}

$sql .= " ORDER BY ac.fecha ASC, conductor ASC ";

$stmt = $conn->prepare($sql);

// bind dinámico
$bind = [];
$bind[] = $types;

for ($i = 0; $i < count($params); $i++) {
    $bindVar = 'b' . $i;
    $$bindVar = $params[$i];
    $bind[] = &$$bindVar;
}

call_user_func_array([$stmt, 'bind_param'], $bind);
$stmt->execute();

// fetch sin mysqlnd
$meta = $stmt->result_metadata();
$fields = [];
$row = [];

while ($field = $meta->fetch_field()) {
    $fields[] = &$row[$field->name];
}

call_user_func_array([$stmt, 'bind_result'], $fields);

$data = [];
while ($stmt->fetch()) {
    $registro = [];
    foreach ($row as $k => $v) {
        $registro[$k] = $v;
    }
    $data[] = $registro;
}

// ============================================================
// 3. EXPORTAR A EXCEL (CSV compatible)
// ============================================================
header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=reporte_mensual.csv");

$output = fopen("php://output", "w");

fputcsv($output, ["Fecha", "Entrada", "Salida", "Código", "Conductor", "Empresa"]);

foreach ($data as $d) {
    fputcsv($output, [
        $d["fecha"],
        $d["hora_entrada"],
        $d["hora_salida"],
        $d["tipo_codigo"],
        $d["conductor"],
        $d["empresa"]
    ]);
}

fclose($output);
exit;
