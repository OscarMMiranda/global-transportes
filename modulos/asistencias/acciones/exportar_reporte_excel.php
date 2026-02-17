<?php
// archivo: /modulos/asistencias/acciones/exportar_reporte_excel.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

$fecha   = isset($_GET['fecha']) ? $_GET['fecha'] : '';
$empresa = isset($_GET['empresa']) ? intval($_GET['empresa']) : 0;

// ========================================
// HEADERS PARA DESCARGA EXCEL
// ========================================
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=reporte_diario_" . date("Ymd_His") . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// ========================================
// CONSULTA AJUSTADA A TU ESTRUCTURA REAL
// ========================================
$sql = "
    SELECT 
        a.id,
        CONCAT(c.nombres, ' ', c.apellidos) AS conductor,
        em.razon_social AS empresa,
        t.descripcion AS tipo,
        a.hora_entrada,
        a.hora_salida,
        a.observacion
    FROM asistencia_conductores a
    INNER JOIN conductores c ON c.id = a.conductor_id
    INNER JOIN empresa em ON em.id = c.empresa_id
    INNER JOIN asistencia_tipos t ON t.id = a.tipo_id
    WHERE a.fecha = ?
";

$params = array($fecha);
$types  = "s";

if ($empresa > 0) {
    $sql .= " AND c.empresa_id = ?";
    $params[] = $empresa;
    $types .= "i";
}

$sql .= " ORDER BY c.nombres ASC";

// ========================================
// PREPARE
// ========================================
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error prepare(): " . $conn->error . "\nSQL: " . $sql);
}

// ========================================
// BIND DINÁMICO (PHP 5.6)
// ========================================
function refValues($arr) {
    $refs = array();
    foreach ($arr as $key => $value) {
        $refs[$key] = &$arr[$key];
    }
    return $refs;
}

call_user_func_array(
    array($stmt, 'bind_param'),
    refValues(array_merge(array($types), $params))
);

// ========================================
// EJECUTAR
// ========================================
$stmt->execute();

// ========================================
// BIND RESULT (COMPATIBLE PHP 5.6 SIN mysqlnd)
// ========================================
$stmt->bind_result(
    $id,
    $conductor,
    $empresa_nom,
    $tipo,
    $entrada,
    $salida,
    $obs
);

// ========================================
// GENERAR EXCEL
// ========================================
echo "<table border='1'>";
echo "<tr style='background:#d0d0d0; font-weight:bold;'>
        <th>Conductor</th>
        <th>Empresa</th>
        <th>Tipo</th>
        <th>Entrada</th>
        <th>Salida</th>
        <th>Observación</th>
      </tr>";

while ($stmt->fetch()) {

    echo "<tr>
            <td>".htmlspecialchars($conductor)."</td>
            <td>".htmlspecialchars($empresa_nom)."</td>
            <td>".htmlspecialchars($tipo)."</td>
            <td>".htmlspecialchars($entrada)."</td>
            <td>".htmlspecialchars($salida)."</td>
            <td>".htmlspecialchars($obs)."</td>
          </tr>";
}

echo "</table>";
