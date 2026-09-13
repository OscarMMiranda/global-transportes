<?php
// archivo: modulos/orden_trabajo/controllers/RegistrarViajeController.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

/* ============================================================
   VALIDAR INPUT
   ============================================================ */
$ot_id         = intval($_POST['orden_trabajo_id']);
$vehiculo_id   = intval($_POST['vehiculo_id']);
$fecha_viaje   = $_POST['fecha_viaje'];
$semana_viaje  = $_POST['semana_viaje'];   // viene como S01
$numero_viaje  = intval($_POST['numero_viaje']);
$origen_id     = intval($_POST['origen']);
$destino_id    = intval($_POST['destino']);
$obs           = $_POST['observaciones'];

/* ============================================================
   VALIDACIONES
   ============================================================ */
if ($ot_id <= 0) {
    echo json_encode(["ok" => false, "msg" => "OT inválida"]);
    exit;
}
if ($vehiculo_id <= 0) {
    echo json_encode(["ok" => false, "msg" => "Debe seleccionar un vehículo"]);
    exit;
}
if (!$fecha_viaje) {
    echo json_encode(["ok" => false, "msg" => "Debe seleccionar la fecha del viaje"]);
    exit;
}

/* ============================================================
   OBTENER CONDUCTOR
   ============================================================ */
$sql_conductor = "
SELECT c.id, c.nombres, c.apellidos
FROM asignaciones_conductor ac
INNER JOIN conductores c ON c.id = ac.conductor_id
WHERE ac.vehiculo_tracto_id = $vehiculo_id
AND ac.estado_id = 1
AND ac.tipo_asignacion = 'tracto'
AND ac.fecha_inicio <= '$fecha_viaje'
AND (ac.fecha_fin IS NULL OR ac.fecha_fin >= '$fecha_viaje')
LIMIT 1
";

$res_conductor = $conn->query($sql_conductor);

if (!$res_conductor || $res_conductor->num_rows === 0) {
    echo json_encode(["ok" => false, "msg" => "No hay conductor asignado para ese vehículo en esa fecha"]);
    exit;
}

$row_conductor = $res_conductor->fetch_assoc();
$conductor_id  = intval($row_conductor["id"]);
$conductor_nombre = $row_conductor["nombres"] . " " . $row_conductor["apellidos"];

/* ============================================================
   GENERAR NUMERO OV (0001-2026)
   ============================================================ */
$year = date('Y', strtotime($fecha_viaje));

$sqlLast = "
SELECT numero_ov
FROM ordenes_vehiculo
WHERE YEAR(fecha_salida) = $year
ORDER BY id DESC
LIMIT 1
";

$resLast = $conn->query($sqlLast);

if ($resLast && $resLast->num_rows > 0) {
    list($num, $yr) = explode('-', $resLast->fetch_assoc()['numero_ov']);
    $nuevo_num = intval($num) + 1;
} else {
    $nuevo_num = 1;
}

$numero_formateado = str_pad($nuevo_num, 4, '0', STR_PAD_LEFT);
$numero_ov = $numero_formateado . '-' . $year;

/* ============================================================
   1. CREAR NUEVA OV
   ============================================================ */
$sqlOV = "
INSERT INTO ordenes_vehiculo (
    orden_trabajo_id,
    vehiculo_id,
    fecha_salida,
    semana_viaje,
    estado,
    numero_ov
) VALUES (
    $ot_id,
    $vehiculo_id,
    '$fecha_viaje',
    '$semana_viaje',
    'activo',
    '$numero_ov'
)
";

$conn->query($sqlOV);
if ($conn->error) {
    echo json_encode(["ok" => false, "msg" => "ERROR OV: " . $conn->error]);
    exit;
}

$orden_vehiculo_id = $conn->insert_id;

/* ============================================================
   2. INSERTAR VIAJE EN viajes_orden
   ============================================================ */
$sql_insert = "
INSERT INTO viajes_orden (
    orden_vehiculo_id,
    conductor_id,
    fecha_salida,
    fecha_llegada,
    distancia_km,
    estado,
    observaciones,
    origen_id,
    destino_id,
    zona_id
) VALUES (
    $orden_vehiculo_id,
    $conductor_id,
    '$fecha_viaje',
    '$fecha_viaje',
    0,
    'pendiente',
    '$obs',
    $origen_id,
    $destino_id,
    1
)
";

$conn->query($sql_insert);
if ($conn->error) {
    echo json_encode(["ok" => false, "msg" => "ERROR VIAJE: " . $conn->error]);
    exit;
}

$viaje_id = $conn->insert_id;

/* ============================================================
   3. INSERTAR EN ot_viajes
   ============================================================ */
$sql_otv = "
INSERT INTO ot_viajes (
    orden_trabajo_id,
    orden_vehiculo_id,
    numero_viaje,
    fecha_viaje,
    semana_viaje,
    estado_viaje,
    observaciones
) VALUES (
    $ot_id,
    $orden_vehiculo_id,
    $numero_viaje,
    '$fecha_viaje',
    '$semana_viaje',
    'pendiente',
    '$obs'
)
";

$conn->query($sql_otv);
if ($conn->error) {
    echo json_encode(["ok" => false, "msg" => "ERROR OTV: " . $conn->error]);
    exit;
}

/* ============================================================
   4. INSERTAR DETALLES DEL VIAJE (OPCIONAL)
   ============================================================ */

$tipo_carga            = $_POST['tipo_carga'] ?? null;
$cantidad              = $_POST['cantidad'] ?? null;
$unidad                = $_POST['unidad'] ?? null;
$contenedor_tipo_id    = $_POST['contenedor_tipo_id'] ?? null;
$contenedor_numero     = $_POST['contenedor_numero'] ?? null;
$mercaderia_tipo_id    = $_POST['mercaderia_tipo_id'] ?? null;
$eir_retiro            = $_POST['eir_retiro'] ?? null;
$eir_devolucion        = $_POST['eir_devolucion'] ?? null;
$almacen_retiro_id     = $_POST['almacen_retiro_id'] ?? null;
$almacen_devolucion_id = $_POST['almacen_devolucion_id'] ?? null;
$pesobruto             = $_POST['pesobruto'] ?? null;
$pesoneto              = $_POST['pesoneto'] ?? null;
$contenedor            = $_POST['contenedor'] ?? null;
$factura_numero        = $_POST['factura_numero'] ?? null;
$dev_ret_id            = $_POST['dev_ret_id'] ?? null;
$obs_detalle           = $_POST['observaciones_detalle'] ?? null;

/*
   Solo insertar si el usuario ingresó ALGO.
*/
if (
    $tipo_carga ||
    $cantidad ||
    $unidad ||
    $contenedor_tipo_id ||
    $contenedor_numero ||
    $mercaderia_tipo_id ||
    $eir_retiro ||
    $eir_devolucion ||
    $almacen_retiro_id ||
    $almacen_devolucion_id ||
    $pesobruto ||
    $pesoneto ||
    $contenedor ||
    $factura_numero ||
    $dev_ret_id ||
    $obs_detalle
) {

    $sql_det = "
    INSERT INTO viaje_detalles (
        orden_vehiculo_id,
        viaje_id,
        tipo_carga,
        cantidad,
        unidad,
        contenedor_tipo_id,
        contenedor_numero,
        mercaderia_tipo_id,
        eir_retiro,
        eir_devolucion,
        almacen_retiro_id,
        almacen_devolucion_id,
        pesobruto,
        pesoneto,
        contenedor,
        factura_numero,
        dev_ret_id,
        observaciones
    ) VALUES (
        $orden_vehiculo_id,
        $viaje_id,
        " . ($tipo_carga ? "'$tipo_carga'" : "NULL") . ",
        " . ($cantidad ? $cantidad : "NULL") . ",
        " . ($unidad ? "'$unidad'" : "NULL") . ",
        " . ($contenedor_tipo_id ? $contenedor_tipo_id : "NULL") . ",
        " . ($contenedor_numero ? "'$contenedor_numero'" : "NULL") . ",
        " . ($mercaderia_tipo_id ? $mercaderia_tipo_id : "NULL") . ",
        " . ($eir_retiro ? "'$eir_retiro'" : "NULL") . ",
        " . ($eir_devolucion ? "'$eir_devolucion'" : "NULL") . ",
        " . ($almacen_retiro_id ? $almacen_retiro_id : "NULL") . ",
        " . ($almacen_devolucion_id ? $almacen_devolucion_id : "NULL") . ",
        " . ($pesobruto ? $pesobruto : "NULL") . ",
        " . ($pesoneto ? $pesoneto : "NULL") . ",
        " . ($contenedor ? "'$contenedor'" : "NULL") . ",
        " . ($factura_numero ? "'$factura_numero'" : "NULL") . ",
        " . ($dev_ret_id ? $dev_ret_id : "NULL") . ",
        " . ($obs_detalle ? "'$obs_detalle'" : "NULL") . "
    )
    ";

    $conn->query($sql_det);

    if ($conn->error) {
        echo json_encode(["ok" => false, "msg" => "ERROR DETALLE VIAJE: " . $conn->error]);
        exit;
    }
}

/* ============================================================
   5. ACTUALIZAR ESTADO DE LA OT → EN PROCESO (2)
   ============================================================ */
$sql_estado = "
UPDATE ordenes_trabajo
SET estado_id = 2
WHERE id = $ot_id
";

$conn->query($sql_estado);

/* ============================================================
   RESPUESTA
   ============================================================ */
echo json_encode([
    "ok" => true,
    "msg" => "Viaje registrado correctamente",
    "numero_ov" => $numero_ov,
    "numero_viaje" => $numero_viaje,
    "conductor" => $conductor_nombre
]);

exit;
