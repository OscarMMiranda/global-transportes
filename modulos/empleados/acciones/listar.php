<?php
	// archivo: /modulos/empleados/acciones/listar.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';

$conn = getConnection();

$draw = intval($_POST['draw']);
$start = intval($_POST['start']);
$length = intval($_POST['length']);
$search = $_POST['search']['value'];

$where = " WHERE e.activo = 1 ";

if ($search != "") {
    $search = $conn->real_escape_string($search);
    $where .= " AND (e.nombres LIKE '%$search%' 
                OR e.apellidos LIKE '%$search%' 
                OR e.dni LIKE '%$search%')";
}

$sql = "SELECT e.id, e.nombres, e.apellidos, e.dni,
               emp.razon_social AS empresa
        FROM empleados e
        INNER JOIN empresa emp ON emp.id = e.empresa_id
        $where
        LIMIT $start, $length";

$res = $conn->query($sql);

if (!$res) {
    echo json_encode([
        "success" => false,
        "message" => "Error SQL listado: " . $conn->error
    ]);
    exit;
}

$data = [];

while ($row = $res->fetch_assoc()) {

    // Obtener roles del empleado
    $roles = [];
    $r = $conn->query("
        SELECT r.nombre 
        FROM empleado_rol er
        INNER JOIN roles_empleados r ON r.id = er.rol_id
        WHERE er.empleado_id = ".$row['id']
    );

    while ($rol = $r->fetch_assoc()) {
        $roles[] = $rol['nombre'];
    }

    $data[] = [
        "id" => $row["id"],
        "nombres" => $row["nombres"],
        "apellidos" => $row["apellidos"],
        "dni" => $row["dni"],
        "empresa" => $row["empresa"],
        "roles" => implode(", ", $roles)
    ];
}

// TOTAL DE REGISTROS
$totalSql = "SELECT COUNT(*) AS total FROM empleados WHERE activo = 1";
$totalRes = $conn->query($totalSql);

if (!$totalRes) {
    echo json_encode([
        "success" => false,
        "message" => "Error SQL total: " . $conn->error
    ]);
    exit;
}

$total = $totalRes->fetch_assoc()['total'];

echo json_encode([
    "draw" => $draw,
    "recordsTotal" => $total,
    "recordsFiltered" => $total,
    "data" => $data
]);
