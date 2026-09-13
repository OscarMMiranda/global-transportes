<?php
// archivo: modulos/orden_trabajo/controllers/CrearOrdenVehiculoController.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

// ============================
// 1. Leer datos del formulario
// ============================
$orden_trabajo_id = isset($_POST['orden_trabajo_id']) ? intval($_POST['orden_trabajo_id']) : 0;
$vehiculo_id      = isset($_POST['vehiculo_id']) ? intval($_POST['vehiculo_id']) : 0;
$conductor_id     = isset($_POST['conductor_id']) ? intval($_POST['conductor_id']) : 0;
$fecha_salida     = isset($_POST['fecha_salida']) ? trim($_POST['fecha_salida']) : '';
$semana_viaje     = isset($_POST['semana_viaje']) ? intval($_POST['semana_viaje']) : 0;

$estado           = 'pendiente';
$observaciones    = NULL;

$usuario_id       = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 1;
$ip_origen        = $_SERVER['REMOTE_ADDR'];

// Validación mínima
if ($orden_trabajo_id <= 0 || $vehiculo_id <= 0 || $conductor_id <= 0 || $fecha_salida == '' || $semana_viaje <= 0) {
    echo json_encode([
        "ok"    => false,
        "error" => "Datos incompletos para crear la Orden de Vehículo."
    ]);
    exit;
}

// ============================
// 2. Crear número de OV básico
// ============================
$anio = date('Y', strtotime($fecha_salida));
$numero_ov = $orden_trabajo_id . '-' . $vehiculo_id . '-' . $anio;

// ============================
// 3. INSERT en ordenes_vehiculo
// ============================
$sqlOv = "
INSERT INTO ordenes_vehiculo (
    numero_ov,
    orden_trabajo_id,
    vehiculo_id,
    conductor_id,
    fecha_salida,
    semana_viaje,
    estado,
    observaciones,
    creado_por,
    usuario_creo,
    ip_origen
) VALUES (
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
)
";

$stmtOv = $conn->prepare($sqlOv);
$stmtOv->bind_param(
    "siiiisssiis",
    $numero_ov,
    $orden_trabajo_id,
    $vehiculo_id,
    $conductor_id,
    $fecha_salida,
    $semana_viaje,
    $estado,
    $observaciones,
    $usuario_id,
    $usuario_id,
    $ip_origen
);

$stmtOv->execute();
$ov_id = $stmtOv->insert_id;
$stmtOv->close();

// ============================
// 4. INSERT en viajes_orden (inicialización mínima)
// ============================
// origen_id y destino_id se llenarán después desde el modal
$origen_id  = 1;   // valor mínimo válido
$destino_id = 1;   // valor mínimo válido

$sqlViaje = "
INSERT INTO viajes_orden (
    orden_vehiculo_id,
    conductor_id,
    fecha_salida,
    fecha_llegada,
    distancia_km,
    estado,
    observaciones,
    usuario_creo,
    origen_id,
    destino_id,
    zona_id
) VALUES (
    ?, ?, ?, NULL, 0, ?, NULL, ?, ?, ?, NULL
)
";

$stmtViaje = $conn->prepare($sqlViaje);
$stmtViaje->bind_param(
    "iissiii",
    $ov_id,
    $conductor_id,
    $fecha_salida,
    $estado,
    $usuario_id,
    $origen_id,
    $destino_id
);

$stmtViaje->execute();
$viaje_id = $stmtViaje->insert_id;
$stmtViaje->close();

// ============================
// 5. INSERT en viaje_detalles (registro base)
// ============================
$sqlDet = "
INSERT INTO viaje_detalles (
    orden_vehiculo_id,
    viaje_id,
    tipo_carga,
    usuario_creo
) VALUES (
    ?, ?, NULL, ?
)
";

$stmtDet = $conn->prepare($sqlDet);
$stmtDet->bind_param("iii", $ov_id, $viaje_id, $usuario_id);
$stmtDet->execute();
$stmtDet->close();

// ============================
// 6. Crear guía cliente mínima
// ============================
$sqlGuiaCliente = "
INSERT INTO guias_cliente (
    id_orden_vehiculo,
    serie,
    numero,
    fecha_emision,
    descripcion,
    archivo_guia,
    tipo_guia_id,
    documento_transporte_id
) VALUES (
    ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL
)
";

$stmtGC = $conn->prepare($sqlGuiaCliente);
$stmtGC->bind_param("i", $ov_id);
$stmtGC->execute();
$guia_cliente_id = $stmtGC->insert_id;
$stmtGC->close();

// ============================
// 7. Inicializar tabla puente cliente
// ============================
$sqlPuenteCliente = "
INSERT INTO ordenes_vehiculo_guias_cliente (
    orden_vehiculo_id,
    viaje_id,
    guia_cliente_id,
    creado_por,
    ip_origen
) VALUES (
    ?, ?, ?, ?, ?
)
";

$stmtPC = $conn->prepare($sqlPuenteCliente);
$stmtPC->bind_param("iiiss", $ov_id, $viaje_id, $guia_cliente_id, $usuario_id, $ip_origen);
$stmtPC->execute();
$stmtPC->close();

// ============================
// 8. Crear guía transporte mínima
// ============================
$sqlGuiaTransporte = "
INSERT INTO guias_transporte (
    id_orden_vehiculo,
    serie,
    numero,
    fecha_emision,
    descripcion,
    archivo_guia,
    tipo_guia_id,
    documento_transporte_id
) VALUES (
    ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL
)
";

$stmtGT = $conn->prepare($sqlGuiaTransporte);
$stmtGT->bind_param("i", $ov_id);
$stmtGT->execute();
$guia_transporte_id = $stmtGT->insert_id;
$stmtGT->close();

// ============================
// 9. Inicializar tabla puente transporte
// ============================
$sqlPuenteTransporte = "
INSERT INTO ordenes_vehiculo_guias_transporte (
    orden_vehiculo_id,
    viaje_id,
    guia_transporte_id,
    creado_por,
    ip_origen
) VALUES (
    ?, ?, ?, ?, ?
)
";

$stmtPT = $conn->prepare($sqlPuenteTransporte);
$stmtPT->bind_param("iiiss", $ov_id, $viaje_id, $guia_transporte_id, $usuario_id, $ip_origen);
$stmtPT->execute();
$stmtPT->close();

// ============================
// 10. Respuesta final
// ============================
echo json_encode([
    "ok" => true,
    "orden_vehiculo_id" => $ov_id,
    "viaje_id" => $viaje_id,
    "guia_cliente_id" => $guia_cliente_id,
    "guia_transporte_id" => $guia_transporte_id,
    "mensaje" => "OV y todas las estructuras base creadas correctamente."
]);
exit;
