<?php
// archivo: /modulos/mantenimiento/tipo_vehiculo/acciones/ping_activos.php
header('Content-Type: text/plain; charset=utf-8');

echo "RUTA ACTUAL: " . __FILE__ . "\n";

require_once __DIR__ . '/../../../includes/conexion.php';
require_once __DIR__ . '/../../../includes/permisos.php';

echo "INCLUDES OK\n";

$conn = getConnection();
if (!$conn) {
    echo "CONEXION FALLÓ\n";
    exit;
}

echo "CONEXION OK\n";

requirePermiso("tipo_vehiculo", "ver");
echo "PERMISO OK\n";

echo "TODO OK HASTA AQUÍ\n";