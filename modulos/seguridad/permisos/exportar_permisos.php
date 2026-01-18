<?php
//  archivo: /modulos/seguridad/permisos/exportar_permisos.php

require_once __DIR__ . '/../../../includes/config.php';
require_once INCLUDES_PATH . '/permisos.php';

requirePermiso("usuarios", "ver");

$conn = getConnection();

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'json';

$sql = "
    SELECT r.nombre AS rol, m.nombre AS modulo, a.nombre AS accion
    FROM permisos_roles pr
    JOIN roles r ON r.id = pr.rol_id
    JOIN modulos m ON m.id = pr.modulo_id
    JOIN acciones a ON a.id = pr.accion_id
    ORDER BY r.nombre, m.nombre, a.nombre
";
$res = $conn->query($sql);

$data = [];
while ($row = $res->fetch_assoc()) {
    $data[] = $row;
}

if ($tipo === "json") {
    header("Content-Type: application/json");
    echo json_encode($data, JSON_PRETTY_PRINT);
    exit;
}

if ($tipo === "csv") {
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=permisos.csv");

    echo "rol,modulo,accion\n";
    foreach ($data as $d) {
        echo "{$d['rol']},{$d['modulo']},{$d['accion']}\n";
    }
    exit;
}

echo "Tipo no válido";