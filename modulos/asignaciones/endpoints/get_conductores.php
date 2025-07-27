<?php
declare(strict_types=1);

// modulos/asignaciones/endpoints/get_conductores.php

header('Content-Type: application/json; charset=UTF-8');

// Carga de configuraciones, sesión y conexión
require_once dirname(dirname(dirname(__DIR__))) . '/includes/config.php';

// Consulta de todos los conductores activos
$sql = "
    SELECT id, nombre
    FROM conductores
    WHERE estado = 'activo'
    ORDER BY nombre
";
$res = mysqli_query($conn, $sql);

$data = $res
    ? mysqli_fetch_all($res, MYSQLI_ASSOC)
    : [];

echo json_encode($data, JSON_UNESCAPED_UNICODE);
exit;
