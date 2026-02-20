<?php
// archivo: /modulos/asistencias/reporte_mensual/acciones/get_conductores.php
// Devuelve la lista de conductores activos, opcionalmente filtrados por empresa, para el reporte mensual de asistencias

require_once __DIR__ . '/../../../../includes/config.php';

$cn = $GLOBALS['db'];

$empresa = isset($_POST['empresa']) ? trim($_POST['empresa']) : '';

$sql = "SELECT id, CONCAT(nombres, ' ', apellidos) AS nombre
        FROM conductores
        WHERE activo = 1";

$params = [];
$types  = "";

if ($empresa !== "") {
    $sql .= " AND empresa_id = ?";
    $params[] = $empresa;
    $types .= "i";
}

$sql .= " ORDER BY apellidos ASC, nombres ASC";

$stmt = $cn->prepare($sql);

if ($types !== "") {

    // Crear array de referencias manualmente (PHP 5.6)
    $bind = [];
    $bind[] = $stmt;
    $bind[] = $types;

    foreach ($params as $key => $value) {
        $bind[] = &$params[$key]; // referencia válida
    }

    call_user_func_array('mysqli_stmt_bind_param', $bind);
}

$stmt->execute();
$res = $stmt->get_result();

$data = [];
while ($row = $res->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    "ok"   => true,
    "data" => $data
]);
