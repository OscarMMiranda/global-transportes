<?php
// ============================================================
// archivo: /modulos/asistencias/acciones/reporte_diario.php
// Backend del reporte diario (JSON) - PHP 5.6 compatible
// ============================================================

header('Content-Type: application/json');

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

// ------------------------------------------------------------
// Validar parámetros
// ------------------------------------------------------------
$fecha = isset($_POST['fecha']) ? trim($_POST['fecha']) : '';
$empresa_id = isset($_POST['empresa_id']) ? trim($_POST['empresa_id']) : '';

if ($fecha == '') {
    echo json_encode([
        'ok' => false,
        'error' => 'Debe seleccionar una fecha.'
    ]);
    exit;
}

// ------------------------------------------------------------
// Consulta REAL según tu estructura
// ------------------------------------------------------------
$sql = "
    SELECT 
        ac.id,
        CONCAT(c.nombres, ' ', c.apellidos) AS conductor,
        e.razon_social AS empresa,
        t.descripcion AS tipo,
        ac.hora_entrada,
        ac.hora_salida,
        ac.observacion
    FROM asistencia_conductores ac
    INNER JOIN conductores c ON c.id = ac.conductor_id
    INNER JOIN empresa e ON e.id = c.empresa_id
    INNER JOIN asistencia_tipos t ON t.id = ac.tipo_id
    WHERE ac.fecha = ?
";

$params = array($fecha);
$types  = "s";

// Filtro por empresa
if ($empresa_id != '') {
    $sql .= " AND e.id = ? ";
    $params[] = $empresa_id;
    $types   .= "i";
}

$sql .= " ORDER BY c.nombres ASC, c.apellidos ASC";

// ------------------------------------------------------------
// Preparar consulta
// ------------------------------------------------------------
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo json_encode([
        'ok' => false,
        'error' => 'Error preparando consulta: ' . mysqli_error($conn)
    ]);
    exit;
}

// ------------------------------------------------------------
// Ejecutar consulta (PHP 5.6 compatible)
// ------------------------------------------------------------
call_user_func_array(
    'mysqli_stmt_bind_param',
    array_merge(array($stmt, $types), refValues($params))
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$data = array();

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

mysqli_stmt_close($stmt);

// ------------------------------------------------------------
// Respuesta final
// ------------------------------------------------------------
echo json_encode([
    'ok' => true,
    'data' => $data
]);

// ------------------------------------------------------------
// Helper para PHP 5.6 (bind_param dinámico)
// ------------------------------------------------------------
function refValues($arr) {
    $refs = array();
    foreach ($arr as $key => $value) {
        $refs[$key] = &$arr[$key];
    }
    return $refs;
}
