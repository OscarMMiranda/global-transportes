<?php
// archivo: /modulos/mantenimiento/tipo_vehiculo/acciones/ver.php
header('Content-Type: application/json');

require_once __DIR__ . '/../../../../includes/conexion.php';
require_once __DIR__ . '/../../../../includes/permisos.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

requirePermiso("tipo_vehiculo", "ver");

if (!isset($_POST['id']) || (int)$_POST['id'] <= 0) {
    echo json_encode(array("ok" => false, "msg" => "ID inválido."));
    exit;
}

$id = (int)$_POST['id'];

$conn = getConnection();

$sql = "
    SELECT 
        tv.id,
        tv.nombre,
        tv.descripcion,
        tv.fecha_creado,
        tv.fecha_modificacion,
        tv.eliminado,
        tv.categoria_id,
        cv.nombre AS categoria_nombre,
        tv.creado_por,
        u.nombre AS creado_por_nombre
    FROM tipo_vehiculo tv
    LEFT JOIN categoria_vehiculo cv ON cv.id = tv.categoria_id
    LEFT JOIN usuarios u ON u.id = tv.creado_por
    WHERE tv.id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {

    $row['estado_badge'] = $row['eliminado']
        ? '<span class="badge bg-danger">Inactivo</span>'
        : '<span class="badge bg-success">Activo</span>';

    echo json_encode(array("ok" => true, "data" => $row));
} else {
    echo json_encode(array("ok" => false, "msg" => "Registro no encontrado."));
}

$stmt->close();
exit;