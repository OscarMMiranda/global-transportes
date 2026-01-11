<?php
// archivo: /modulos/vehiculos/vistas/info.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Validación del ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<div class='alert alert-danger'>ID inválido</div>");
}

$id = intval($_GET['id']);

// Cargar config.php (que carga conexion.php)
require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

// Obtener conexión
$conn = getConnection();
if (!$conn) {
    die("<div class='alert alert-danger'>Error de conexión a la base de datos.</div>");
}

// Consulta REAL según tu estructura
$sql = "
SELECT 
    v.placa,
    v.modelo,
    v.anio,
    mv.nombre AS marca,
    tv.nombre AS tipo,
    ev.nombre AS estado,
    cv.nombre AS configuracion,
    e.razon_social AS empresa,
    v.observaciones,
    v.fecha_creado,
    v.fecha_modificacion,
    v.creado_por,
    v.modificado_por
FROM vehiculos v
LEFT JOIN marca_vehiculo mv ON mv.id = v.marca_id
LEFT JOIN tipo_vehiculo tv ON tv.id = v.tipo_id
LEFT JOIN estado_vehiculo ev ON ev.id = v.estado_id
LEFT JOIN configuracion_vehiculo cv ON cv.id = v.configuracion_id
LEFT JOIN empresa e ON e.id = v.empresa_id
WHERE v.id = $id
LIMIT 1
";

$result = $conn->query($sql);

if (!$result) {
    die("<div class='alert alert-danger'>Error en la consulta SQL: " . $conn->error . "</div>");
}

if ($result->num_rows === 0) {
    echo "<div class='alert alert-warning'>Vehículo no encontrado</div>";
    exit;
}

$vehiculo = $result->fetch_assoc();
?>

<!-- CONTENIDO DE LA PESTAÑA INFORMACIÓN -->
<div class="info-section">

    <h5 class="section-title">
        <i class="fa-solid fa-circle-info me-2 text-primary"></i>
        Información general
    </h5>

    <div class="row mb-4">

        <div class="col-md-4">
            <label class="info-label">Placa</label>
            <div class="info-value"><?= htmlspecialchars($vehiculo['placa']) ?></div>
        </div>

        <div class="col-md-4">
            <label class="info-label">Marca</label>
            <div class="info-value"><?= htmlspecialchars($vehiculo['marca']) ?></div>
        </div>

        <div class="col-md-4">
            <label class="info-label">Modelo</label>
            <div class="info-value"><?= htmlspecialchars($vehiculo['modelo']) ?></div>
        </div>

        <div class="col-md-4">
            <label class="info-label">Año</label>
            <div class="info-value"><?= htmlspecialchars($vehiculo['anio']) ?></div>
        </div>

        <div class="col-md-4">
            <label class="info-label">Tipo</label>
            <div class="info-value"><?= htmlspecialchars($vehiculo['tipo']) ?></div>
        </div>

        <div class="col-md-4">
            <label class="info-label">Estado</label>
            <div class="info-value">
                <span class="badge <?= ($vehiculo['estado'] === 'ACTIVO') ? 'bg-success' : 'bg-secondary' ?>">
                    <?= htmlspecialchars($vehiculo['estado']) ?>
                </span>
            </div>
        </div>

        <div class="col-md-4">
            <label class="info-label">Configuración</label>
            <div class="info-value"><?= htmlspecialchars($vehiculo['configuracion']) ?></div>
        </div>

        <div class="col-md-8">
            <label class="info-label">Empresa</label>
            <div class="info-value"><?= htmlspecialchars($vehiculo['empresa']) ?></div>
        </div>

    </div>

    <h5 class="section-title">
        <i class="fa-solid fa-note-sticky me-2 text-primary"></i>
        Observaciones
    </h5>

    <div class="info-observaciones">
        <?= nl2br(htmlspecialchars($vehiculo['observaciones'])) ?>
    </div>

</div>