<?php
// archivo: /modulos/asistencias/reporte_mensual/acciones/obtener_reporte_mensual.php
header('Content-Type: application/json');

require_once __DIR__ . '/../../../../includes/config.php';
$conn = getConnection();

// ============================================================
// 1. FILTROS
// ============================================================
$empresa_id_raw   = isset($_POST['empresa_id']) ? $_POST['empresa_id'] : "";
$conductor_id_raw = isset($_POST['conductor_id']) ? $_POST['conductor_id'] : "";
$mes              = isset($_POST['mes']) ? (int)$_POST['mes'] : 0;
$anio             = isset($_POST['anio']) ? (int)$_POST['anio'] : 0;

// permitir "todas las empresas"
if ($empresa_id_raw === "" || $empresa_id_raw === "0" || $empresa_id_raw === "ALL") {
    $empresa_id = 0;
} else {
    $empresa_id = (int)$empresa_id_raw;
}

// permitir "todos los conductores"
if ($conductor_id_raw === "" || $conductor_id_raw === "0" || $conductor_id_raw === "ALL") {
    $conductor_id = 0;
} else {
    $conductor_id = (int)$conductor_id_raw;
}

// validar solo mes y año
if ($mes < 1 || $mes > 12 || $anio < 2000) {
    echo json_encode(["ok" => false, "msg" => "Debe seleccionar mes y año"]);
    exit;
}



// ============================================================
// 2. CONSULTA PRINCIPAL
// ============================================================
$sql = "
    SELECT 
        ac.id,
        ac.fecha,
        ac.hora_entrada,
        ac.hora_salida,
        ac.observacion,
        ac.tipo_codigo,
        at.descripcion AS tipo,
        c.id AS conductor_id,
        CONCAT(c.nombres, ' ', c.apellidos) AS conductor,
        e.razon_social AS empresa
    FROM asistencia_conductores ac
    INNER JOIN conductores c ON c.id = ac.conductor_id
    INNER JOIN empresa e ON e.id = c.empresa_id
    INNER JOIN asistencia_tipos at ON at.id = ac.tipo_id
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

// $sql .= " ORDER BY ac.fecha ASC, c.apellidos ASC, c.nombres ASC ";

$sql .= " ORDER BY c.apellidos ASC, c.nombres ASC, ac.fecha ASC ";



$stmt = $conn->prepare($sql);

// ============================================================
// 3. BIND PARAM DINÁMICO (PHP 5.6)
// ============================================================
$bind = [];
$bind[] = $types;

for ($i = 0; $i < count($params); $i++) {
    $bindVar = 'b' . $i;
    $$bindVar = $params[$i];
    $bind[] = &$$bindVar;
}

call_user_func_array([$stmt, 'bind_param'], $bind);

// ============================================================
// 4. EJECUTAR
// ============================================================
$stmt->execute();

// ============================================================
// 5. FETCH COMPATIBLE PHP 5.6 SIN mysqlnd
// ============================================================
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
// 6. CALCULAR TOTALES
// ============================================================
$totales = [
    "total_asistencias" => 0,
    "total_faltas"      => 0,
    "total_vacaciones"  => 0,
    "total_permisos"    => 0,
    "total_medicos"     => 0,
    "total_feriados"    => 0,
    "total_horas"       => 0,
    "total_horas_extra" => 0
];

$min_trabajados = 0;
$min_extra = 0;

foreach ($data as $a) {

    $codigo = $a["tipo_codigo"];

    switch ($codigo) {

        case "A": // Asistencia real
            $totales["total_asistencias"]++;

            if ($a["hora_entrada"] != "00:00:00" && $a["hora_salida"] != "00:00:00") {

                $ini = strtotime($a["hora_entrada"]);
                $fin = strtotime($a["hora_salida"]);

                if ($fin > $ini) {
                    $min = ($fin - $ini) / 60;
                    $min_trabajados += $min;

                    if ($min > 480) {
                        $min_extra += ($min - 480);
                    }
                }
            }
            break;

        case "FI":
        case "FJ":
            $totales["total_faltas"]++;
            break;

        case "VA":
            $totales["total_vacaciones"]++;
            break;

        case "PE":
            $totales["total_permisos"]++;
            break;

        case "ME":
            $totales["total_medicos"]++;
            break;

        case "FN":
        case "FE":
            $totales["total_feriados"]++;
            break;
    }
}

$totales["total_horas"]       = round($min_trabajados / 60, 2);
$totales["total_horas_extra"] = round($min_extra / 60, 2);

// ============================================================
// 7. RESPUESTA FINAL
// ============================================================
echo json_encode([
    "ok"      => true,
    "data"    => $data,
    "totales" => $totales
]);
exit;
