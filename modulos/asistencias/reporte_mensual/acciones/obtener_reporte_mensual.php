<?php
    //  archivo : /modulos/asistencias/reporte_mensual/acciones/obtener_reporte_mensual.php
    //  autor   : Oscar M. Miranda Z
    //  Este archivo obtiene los datos para el reporte mensual de asistencias

header('Content-Type: application/json');

require_once __DIR__ . '/../../../../includes/config.php';
$conn = getConnection();

// Leer filtros
$empresa   = isset($_POST['empresa'])   && $_POST['empresa'] !== '' ? (int)$_POST['empresa']   : null;
$conductor = isset($_POST['conductor']) && $_POST['conductor'] !== '' ? (int)$_POST['conductor'] : null;
$mes       = isset($_POST['mes'])       ? (int)$_POST['mes']        : 0;
$anio      = isset($_POST['anio'])      ? (int)$_POST['anio']       : 0;
$vista     = isset($_POST['vista'])     ? trim($_POST['vista'])     : 'tabla';

// Validaciones
if ($mes < 1 || $mes > 12) {
    echo json_encode(["ok" => false, "msg" => "Mes inválido", "step" => 3]);
    exit;
}

if ($anio < 2000) {
    echo json_encode(["ok" => false, "msg" => "Año inválido", "step" => 3]);
    exit;
}

// SQL base
$sql = "
    SELECT 
        ac.id,
        ac.fecha,
        ac.hora_entrada,
        ac.hora_salida,
        ac.observacion,
        ac.es_feriado,
        at.descripcion AS tipo_asistencia,
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

// Parámetros base
$params = [$mes, $anio];
$types  = "ii";

// Filtro empresa
if ($empresa !== null) {
    $sql .= " AND c.empresa_id = ? ";
    $params[] = $empresa;
    $types   .= "i";
}

// Filtro conductor
if ($conductor !== null) {
    $sql .= " AND ac.conductor_id = ? ";
    $params[] = $conductor;
    $types   .= "i";
}

$sql .= " ORDER BY ac.fecha ASC, c.apellidos ASC, c.nombres ASC ";

// PREPARE
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "ok" => false,
        "step" => 5,
        "msg" => "Error al preparar consulta",
        "error" => $conn->error
    ]);
    exit;
}

// BIND_PARAM dinámico (PHP 5.6)
$bind_names = [];
$bind_names[] = $types;

for ($i = 0; $i < count($params); $i++) {
    $bind_name = 'bind' . $i;
    $$bind_name = $params[$i];
    $bind_names[] = &$$bind_name;
}

call_user_func_array([$stmt, 'bind_param'], $bind_names);

// EXECUTE
if (!$stmt->execute()) {
    echo json_encode([
        "ok" => false,
        "step" => 7,
        "msg" => "Error al ejecutar consulta",
        "error" => $stmt->error
    ]);
    exit;
}

// FETCH (compatibilidad PHP 5.6 sin mysqlnd)
$resultados = [];

$meta = $stmt->result_metadata();
$fields = [];
$row = [];

while ($field = $meta->fetch_field()) {
    $fields[] = &$row[$field->name];
}

call_user_func_array([$stmt, 'bind_result'], $fields);

while ($stmt->fetch()) {
    $registro = [];
    foreach ($row as $key => $val) {
        $registro[$key] = $val;
    }
    $resultados[] = $registro;
}

// RESPUESTA FINAL
echo json_encode([
    "ok" => true,
    "step" => 9,
    "msg" => "Reporte generado",
    "total" => count($resultados),
    "data" => $resultados,
    "filtros" => [
        "empresa" => $empresa,
        "conductor" => $conductor,
        "mes" => $mes,
        "anio" => $anio,
        "vista" => $vista
    ]
]);
exit;
