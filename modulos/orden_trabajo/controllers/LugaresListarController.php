<?php
//  archivo: /modulos/orden_trabajo/controllers/LugaresListarController.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

$sql = "
SELECT id, nombre
FROM lugares
WHERE estado = 'activo'
ORDER BY nombre ASC
";

$res = $conn->query($sql);

$lugares = array();
if ($res && $res->num_rows > 0) {
    while ($r = $res->fetch_assoc()) {
        $lugares[] = $r;
    }
}

header('Content-Type: application/json');
echo json_encode($lugares);
exit;
