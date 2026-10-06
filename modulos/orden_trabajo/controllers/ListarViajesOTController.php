<?php
// ======================================================
//  CONTROLADOR: ListarViajesOTController.php
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Listar viajes asociados a una OT
//  GLOBAL 2026 — Arquitectura Limpia
// ======================================================

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

// ============================
// Validación de parámetro
// ============================
$ot_id = isset($_POST['orden_trabajo_id']) ? intval($_POST['orden_trabajo_id']) : 0;

if ($ot_id <= 0) {
    echo json_encode([
        "ok" => false,
        "error" => "ID de OT inválido"
    ]);
    exit;
}

// ============================
// Consulta principal
// ============================
$sql = "
SELECT 
    ov.id,
    ov.numero_ov AS numero_viaje,
    ov.fecha_salida,
    ov.semana_viaje,
    ov.estado,
    ov.observaciones,

    vh.placa AS vehiculo,

    c.nombres,
    c.apellidos

FROM ordenes_vehiculo ov
LEFT JOIN vehiculos vh ON vh.id = ov.vehiculo_id
LEFT JOIN conductores c ON c.id = ov.conductor_id

WHERE ov.orden_trabajo_id = $ot_id
ORDER BY ov.id ASC
";

$res = $conn->query($sql);

if (!$res) {
    echo json_encode([
        "ok" => false,
        "error" => $conn->error
    ]);
    exit;
}

$data = [];

// ============================
// Procesamiento de filas
// ============================
while ($row = $res->fetch_assoc()) {

    // Fecha corporativa
    $fecha = $row["fecha_salida"] ? date("d/m/Y", strtotime($row["fecha_salida"])) : "-";

// Semana corporativa (S01-2026)

// Valor crudo desde BD: puede ser "2026-W01", "2026-W1", "01", "1"
$semana_raw = trim($row["semana_viaje"]);
$anio = substr($row["fecha_salida"], 0, 4); // Año real desde fecha

// Caso 1: formato ISO "2026-W01" o "2026-W1"
if (strpos($semana_raw, "W") !== false) {
    // Extraer lo que viene después de "W"
    $semana_num = str_replace("W", "", substr($semana_raw, 5));
}
// Caso 2: solo número "01" o "1"
else {
    $semana_num = $semana_raw;
}

// Normalizar a 2 dígitos
$semana_num = str_pad($semana_num, 2, "0", STR_PAD_LEFT);

// Formato final corporativo
$semana = "S" . $semana_num . "-" . $anio;



    // Estado corporativo
    $estado = ucfirst(strtolower($row["estado"]));

    // Vehículo
    $vehiculo = $row["vehiculo"] ? $row["vehiculo"] : "-";

  // ============================
// Conductor: Inicial + Primer Apellido
// ============================
$nombres = trim($row["nombres"]);
$apellidos = trim($row["apellidos"]);

$primerNombre = explode(" ", $nombres)[0];
$primerApellido = explode(" ", $apellidos)[0];

$inicial = strtoupper(substr($primerNombre, 0, 1)); // Inicial del nombre

$conductor = $inicial . ". " . $primerApellido;     // Ej: J. Quispe

    // ============================
    // Construcción del registro
    // ============================
    $data[] = [
        "numero_viaje"    => $row["numero_viaje"],
        "fecha"           => $fecha,
        "semana"          => $semana,
        "vehiculo"        => $vehiculo,
        "conductor"       => $conductor,
        "origen"          => "-",   // Se llenará después
        "destino"         => "-",   // Se llenará después
        "tipo_mercaderia" => "-",   // Se llenará después
        "estado"          => $estado
    ];
}

// ============================
// Respuesta final
// ============================
echo json_encode([
    "ok" => true,
    "data" => $data
]);
exit;
