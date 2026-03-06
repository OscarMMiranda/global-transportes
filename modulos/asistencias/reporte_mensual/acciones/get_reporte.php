<?php
// archivo: /modulos/asistencias/reporte_mensual/acciones/get_reporte.php
header('Content-Type: application/json');

require_once __DIR__ . '/../../../../includes/config.php';
$conn = getConnection();

// ============================================================
// 1. RECIBIR FILTROS
// ============================================================
$empresa_id   = isset($_POST['empresa_id']) ? intval($_POST['empresa_id']) : 0;
$conductor_id = isset($_POST['conductor_id']) ? intval($_POST['conductor_id']) : 0;
$mes          = isset($_POST['mes']) ? intval($_POST['mes']) : 0;
$anio         = isset($_POST['anio']) ? intval($_POST['anio']) : 0;

if ($empresa_id <= 0 || $conductor_id <= 0 || $mes <= 0 || $anio <= 0) {
    echo json_encode(array('ok' => false, 'error' => 'Filtros incompletos'));
    exit;
}

// ============================================================
// 2. CONSULTA PRINCIPAL
// ============================================================
$sql = "
    SELECT 
        ac.id,
        ac.fecha,
        ac.tipo_id,
        ac.tipo_codigo,
        at.descripcion AS tipo,
        ac.hora_entrada,
        ac.hora_salida,
        ac.observacion
    FROM asistencia_conductores ac
    INNER JOIN conductores c ON ac.conductor_id = c.id
    LEFT JOIN asistencia_tipos at ON ac.tipo_id = at.id
    WHERE c.empresa_id = ?
      AND ac.conductor_id = ?
      AND MONTH(ac.fecha) = ?
      AND YEAR(ac.fecha) = ?
    ORDER BY ac.fecha ASC
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "iiii", $empresa_id, $conductor_id, $mes, $anio);
mysqli_stmt_execute($stmt);

// ============================================================
// 3. OBTENER RESULTADOS (PHP 5.6 SIN mysqlnd)
// ============================================================
error_log("DEBUG: Entrando a metadata...");

$meta = mysqli_stmt_result_metadata($stmt);

if (!$meta) {
    error_log("ERROR: metadata es NULL");
}

$fields = $meta ? $meta->fetch_fields() : array();

error_log("DEBUG: Campos detectados: " . count($fields));

$bindVars = array();
$row = array();

foreach ($fields as $field) {
    $bindVars[] = &$row[$field->name];
}

if (empty($bindVars)) {
    error_log("ERROR: bindVars está vacío. No se vincularon columnas.");
}

call_user_func_array(array($stmt, 'bind_result'), $bindVars);

error_log("DEBUG: bind_result ejecutado");

$asistencias = array();
$contador = 0;

while (mysqli_stmt_fetch($stmt)) {
    $contador++;
    $asistencias[] = array_map(function($v) { return $v; }, $row);
}

error_log("DEBUG: Filas obtenidas con fetch(): " . $contador);
error_log("DEBUG ASISTENCIAS: " . print_r($asistencias, true));

// ============================================================
// 4. TOTALES
// ============================================================
$totales = array(
    'total_asistencias' => 0,
    'total_faltas'      => 0,
    'total_vacaciones'  => 0,
    'total_permisos'    => 0,
    'total_medicos'     => 0,
    'total_feriados'    => 0,
    'total_horas'       => 0,
    'total_horas_extra' => 0
);

$total_horas_min = 0;
$total_horas_extra_min = 0;

// ============================================================
// 5. CONTAR Y CALCULAR HORAS
// ============================================================
foreach ($asistencias as $a) {

    $codigo = $a['tipo_codigo'];

    switch ($codigo) {

        case 'A': // Asistencia real
            $totales['total_asistencias']++;

            if ($a['hora_entrada'] != "00:00:00" && $a['hora_salida'] != "00:00:00") {

                $inicio = strtotime($a['hora_entrada']);
                $fin    = strtotime($a['hora_salida']);

                if ($fin > $inicio) {
                    $min = ($fin - $inicio) / 60;
                    $total_horas_min += $min;

                    if ($min > 480) {
                        $total_horas_extra_min += ($min - 480);
                    }
                }
            }
            break;

        case 'FI':
        case 'FJ':
            $totales['total_faltas']++;
            break;

        case 'VA':
            $totales['total_vacaciones']++;
            break;

        case 'PE':
            $totales['total_permisos']++;
            break;

        case 'ME':
            $totales['total_medicos']++;
            break;

        case 'FN':
        case 'FE':
            $totales['total_feriados']++;
            break;
    }
}

// ============================================================
// 6. HORAS FINALES
// ============================================================
$totales['total_horas']       = round($total_horas_min / 60, 2);
$totales['total_horas_extra'] = round($total_horas_extra_min / 60, 2);

// ============================================================
// 7. RESPUESTA FINAL
// ============================================================
echo json_encode(array(
    'ok'      => true,
    'data'    => $asistencias,
    'totales' => $totales
));
