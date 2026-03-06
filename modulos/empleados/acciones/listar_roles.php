<?php
header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';

$conn = getConnection();

$empleadoId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 1. Obtener roles desde roles_empleados
$sql = "SELECT id, nombre, descripcion FROM roles_empleados WHERE activo = 1 ORDER BY nombre ASC";
$res = $conn->query($sql);

$roles = [];

// 2. Obtener roles asignados desde empleado_rol
$rolesAsignados = [];
if ($empleadoId > 0) {
    $q = $conn->query("SELECT rol_id FROM empleado_rol WHERE empleado_id = $empleadoId");
    while ($r = $q->fetch_assoc()) {
        $rolesAsignados[] = intval($r['rol_id']);
    }
}

// 3. Armar respuesta
while ($row = $res->fetch_assoc()) {
    $row['asignado'] = in_array(intval($row['id']), $rolesAsignados);
    $roles[] = $row;
}

echo json_encode([
    "success" => true,
    "data" => $roles
]);
