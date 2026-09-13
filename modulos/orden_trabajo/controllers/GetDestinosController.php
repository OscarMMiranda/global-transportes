<?php
    //  archivo: modulos/orden_trabajo/controllers/GetDestinosController.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

/*
   UNIFICAR ENTIDADES + LOCALES + DESTINOS
*/

$sql = "

(
    SELECT 
        e.id,
        e.nombre,
        'entidad' AS tipo,
        e.direccion AS direccion
    FROM entidades e
    WHERE e.estado = 'activo'
)

UNION ALL

(
    SELECT
        l.id,
        l.nombre,
        'local' AS tipo,
        l.direccion AS direccion
    FROM locales l
)

UNION ALL

(
    SELECT
        d.id,
        d.nombre,
        'destino' AS tipo,
        d.direccion AS direccion
    FROM destinos d
    WHERE d.estado = 'activo'
)

ORDER BY nombre ASC
";

$res = $conn->query($sql);

if (!$res) {
    echo json_encode(["ok" => false, "msg" => $conn->error]);
    exit;
}

$data = [];

while ($row = $res->fetch_assoc()) {
    $data[] = [
        "id"        => intval($row["id"]),
        "nombre"    => $row["nombre"],
        "tipo"      => $row["tipo"],
        "direccion" => $row["direccion"]
    ];
}

echo json_encode([
    "ok"   => true,
    "data" => $data
]);
exit;
