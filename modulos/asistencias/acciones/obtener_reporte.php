<?php
// archivo: modulos/asistencias/acciones/obtener_reporte.php
header('Content-Type: application/json');

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

$fecha   = isset($_GET['fecha']) ? $_GET['fecha'] : null;
$empresa = isset($_GET['empresa']) ? $_GET['empresa'] : null;

if (!$fecha) {
    echo json_encode([
        'ok' => false,
        'error' => 'La fecha es obligatoria'
    ]);
    exit;
}

$sql = "
    SELECT 
        a.id AS id,
        CONCAT(c.nombres, ' ', c.apellidos) AS conductor,
        e.razon_social AS empresa,
        t.descripcion AS tipo,
        a.hora_entrada,
        a.hora_salida,
        a.observacion
    FROM asistencia_conductores a
    INNER JOIN conductores c ON c.id = a.conductor_id
    INNER JOIN empresa e ON e.id = c.empresa_id
    INNER JOIN asistencia_tipos t ON t.id = a.tipo_id
    WHERE a.fecha = ?
";


$params = array($fecha);
$types  = "s";

if (!empty($empresa)) {
    $sql .= " AND e.id = ? ";
    $params[] = $empresa;
    $types   .= "i";
}

$sql .= " ORDER BY c.nombres ASC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode([
        'ok' => false,
        'error' => 'Error al preparar consulta: ' . $conn->error
    ]);
    exit;
}

$tmp = array();
$tmp[] = $types;
foreach ($params as $key => $value) {
    $tmp[] = &$params[$key];
}

call_user_func_array(array($stmt, 'bind_param'), $tmp);

$stmt->execute();
$result = $stmt->get_result();

$data = array();

while ($row = $result->fetch_assoc()) {
    $data[] = array(
        'id'            => $row['id'],
        'conductor'     => $row['conductor'],
        'empresa'       => $row['empresa'],
        'tipo'          => $row['tipo'], // ahora sí existe
        'hora_entrada'  => $row['hora_entrada'],
        'hora_salida'   => $row['hora_salida'],
        'observacion'   => $row['observacion']
    );
}

$stmt->close();

echo json_encode([
    'ok'   => true,
    'data' => $data
]);
