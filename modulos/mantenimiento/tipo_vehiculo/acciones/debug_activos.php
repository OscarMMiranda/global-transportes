<?php
header('Content-Type: text/plain; charset=utf-8');

echo "1) INICIO\n";

require_once __DIR__ . '/../../../../includes/conexion.php';
echo "2) CARGO CONEXION\n";

require_once __DIR__ . '/../../../../includes/permisos.php';
echo "3) CARGO PERMISOS\n";

$conn = getConnection();
echo "4) CONEXION OK\n";

requirePermiso("tipo_vehiculo", "ver");
echo "5) PERMISO OK\n";

$sql = "SELECT id, nombre FROM tipo_vehiculo WHERE estado = 1 LIMIT 5";
echo "6) SQL PREPARADO\n";

$stmt = $conn->prepare($sql);
echo "7) PREPARE OK\n";

$stmt->execute();
echo "8) EXECUTE OK\n";

$stmt->store_result();
echo "9) STORE RESULT OK\n";

$stmt->bind_result($id, $nombre);
echo "10) BIND RESULT OK\n";

while ($stmt->fetch()) {
    echo "ROW: $id - $nombre\n";
}

echo "11) FIN OK\n";
