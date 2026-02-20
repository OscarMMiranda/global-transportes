<?php
//  archivo : /modulos/asistencias/reporte_mensual/acciones/exportar_excel.php
//  autor   : Oscar M. Miranda Z
//  Este archivo obtiene los datos para el reporte mensual de asistencias

require_once __DIR__ . '/../../../../includes/config.php';
$conn = getConnection();

// Leer filtros
$empresa   = isset($_GET['empresa'])   && $_GET['empresa'] !== '' ? (int)$_GET['empresa']   : null;
$conductor = isset($_GET['conductor']) && $_GET['conductor'] !== '' ? (int)$_GET['conductor'] : null;
$mes       = isset($_GET['mes'])       ? (int)$_GET['mes']        : 0;
$anio      = isset($_GET['anio'])      ? (int)$_GET['anio']       : 0;

// Validaciones
if ($mes < 1 || $mes > 12) die("Mes inválido");
if ($anio < 2000) die("Año inválido");

// SQL base
$sql = "
    SELECT 
        ac.fecha,
        ac.hora_entrada,
        ac.hora_salida,
        ac.observacion,
        ac.es_feriado,
        at.descripcion AS tipo_asistencia,
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

if ($empresa !== null) {
    $sql .= " AND c.empresa_id = ? ";
    $params[] = $empresa;
    $types   .= "i";
}

if ($conductor !== null) {
    $sql .= " AND ac.conductor_id = ? ";
    $params[] = $conductor;
    $types   .= "i";
}

$sql .= " ORDER BY ac.fecha ASC, c.apellidos ASC, c.nombres ASC ";

// PREPARE
$stmt = $conn->prepare($sql);
if (!$stmt) die("Error prepare: " . $conn->error);

// BIND
$bind = [];
$bind[] = $types;
for ($i = 0; $i < count($params); $i++) {
    $bindName = "b".$i;
    $$bindName = $params[$i];
    $bind[] = &$$bindName;
}
call_user_func_array([$stmt, "bind_param"], $bind);

// EXECUTE
$stmt->execute();

// FETCH (PHP 5.6 sin mysqlnd)
$meta = $stmt->result_metadata();
$fields = [];
$row = [];
while ($field = $meta->fetch_field()) {
    $fields[] = &$row[$field->name];
}
call_user_func_array([$stmt, "bind_result"], $fields);

$datos = [];
while ($stmt->fetch()) {
    $registro = [];
    foreach ($row as $k => $v) {
        $registro[$k] = $v;
    }
    $datos[] = $registro;
}

// ===============================================
//  GENERAR EXCEL (XLS clásico compatible)
// ===============================================

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=reporte_mensual.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "
<style>
table { border-collapse: collapse; font-family: Arial; font-size: 12px; }
td { border: 1px solid #000; padding: 4px; }
.header { background: #d9d9d9; font-weight: bold; }
</style>
";


echo "<table border='1' cellpadding='4'>";
echo "<tr style='background:#d9d9d9; font-weight:bold'>
        <td>Fecha</td>
        <td>Conductor</td>
        <td>Empresa</td>
        <td>Tipo</td>
        <td>Hora Entrada</td>
        <td>Hora Salida</td>
        <td>Horas Trabajadas</td>
        <td>Observación</td>
      </tr>";

foreach ($datos as $d) {

    // Calcular horas trabajadas
    $h1 = $d['hora_entrada'];
    $h2 = $d['hora_salida'];

    $horas = "00:00";
    if ($h1 && $h2 && $h1 !== "00:00:00" && $h2 !== "00:00:00") {
        $t1 = strtotime($h1);
        $t2 = strtotime($h2);
        $diff = $t2 - $t1;
        if ($diff > 0) {
            $h = floor($diff / 3600);
            $m = floor(($diff % 3600) / 60);
            $horas = sprintf("%02d:%02d", $h, $m);
        }
    }

    echo "<tr>
            <td>{$d['fecha']}</td>
            <td>{$d['conductor']}</td>
            <td>{$d['empresa']}</td>
            <td>{$d['tipo_asistencia']}</td>
            <td>{$d['hora_entrada']}</td>
            <td>{$d['hora_salida']}</td>
            <td>{$horas}</td>
            <td>{$d['observacion']}</td>
          </tr>";
}

echo "</table>";
exit;
