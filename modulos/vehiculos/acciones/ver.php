<?php
// archivo: /modulos/vehiculos/acciones/ver.php 
// Devuelve los datos completos de un vehículo en formato JSON
// incluyendo fotos e historial.
// Parámetro esperado: ?id=ID_DEL_VEHICULO
// Respuesta: JSON con los datos del vehículo

require_once __DIR__ . '/../../../includes/config.php';
require_once INCLUDES_PATH . '/funciones.php';

$conn = getConnection();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(["error" => "ID no proporcionado o inválido"]);
    exit;
}

$id = intval($_GET['id']);

/* ===========================
   1. DATOS PRINCIPALES
   =========================== */
$sql = "
    SELECT 
        v.id,
        v.placa,

        v.tipo_id,
        t.nombre AS tipo_nombre,

        v.marca_id,
        m.nombre AS marca_nombre,

        v.estado_id,
        e.nombre AS estado_nombre,

        v.configuracion_id,
        c.nombre AS configuracion_nombre,

        v.modelo,
        v.anio,

        v.empresa_id,
        emp.razon_social AS empresa_nombre,

        v.observaciones,
        v.activo
    FROM vehiculos v
    LEFT JOIN marca_vehiculo m ON m.id = v.marca_id
    LEFT JOIN tipo_vehiculo t ON t.id = v.tipo_id
    LEFT JOIN estado_vehiculo e ON e.id = v.estado_id
    LEFT JOIN configuracion_vehiculo c ON c.id = v.configuracion_id
    LEFT JOIN empresa emp ON emp.id = v.empresa_id
    WHERE v.id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo json_encode(["error" => "Vehículo no encontrado"]);
    exit;
}

$veh = $res->fetch_assoc();

/* ===========================
   2. FOTOS DEL VEHÍCULO
   =========================== */
$sqlFotos = "
    SELECT ruta_archivo AS ruta, descripcion
    FROM vehiculo_fotos
    WHERE id_vehiculo = ?
";

$stmtFotos = $conn->prepare($sqlFotos);
$stmtFotos->bind_param("i", $id);
$stmtFotos->execute();
$resFotos = $stmtFotos->get_result();

$fotos = [];
while ($f = $resFotos->fetch_assoc()) {
    $fotos[] = [
        "ruta" => "/uploads/vehiculos/" . $f["ruta"],
        "descripcion" => $f["descripcion"]
    ];
}

/* ===========================
   3. HISTORIAL DEL VEHÍCULO
   =========================== */
$sqlHist = "
    SELECT 
        h.estado_anterior,
        h.estado_nuevo,
        h.motivo,
        h.fecha,
        CONCAT(u.nombre, ' ', u.apellido) AS usuario
    FROM vehiculo_historial h
    LEFT JOIN usuarios u ON u.id = h.usuario_id
    WHERE h.vehiculo_id = ?
    ORDER BY h.fecha DESC
";

$stmtHist = $conn->prepare($sqlHist);
$stmtHist->bind_param("i", $id);
$stmtHist->execute();
$resHist = $stmtHist->get_result();

$historial = [];
while ($h = $resHist->fetch_assoc()) {
    $historial[] = $h;
}

/* ===========================
   4. ARMAR RESPUESTA FINAL
   =========================== */
$veh["fotos"] = $fotos;
$veh["historial"] = $historial;

echo json_encode($veh);