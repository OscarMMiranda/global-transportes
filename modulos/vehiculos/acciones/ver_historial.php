<?php
// archivo: /modulos/vehiculos/acciones/ver_historial.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

$idVehiculo = isset($_POST['id']) ? intval($_POST['id']) : 0;

$eventos = [];

/* ============================================================
   1. ASIGNACIONES (TRACTO / REMOLQUE / COMBINADO)
   ============================================================ */
$sql = "
    SELECT 
        ac.fecha_inicio AS fecha,
        'Asignación' AS tipo,
        CONCAT(
            'Asignado al conductor: ', c.nombres, ' ', c.apellidos,
            ' (', ac.tipo_asignacion, ')'
        ) AS detalle
    FROM asignaciones_conductor ac
    JOIN conductores c ON ac.conductor_id = c.id
    WHERE ac.fecha_borrado IS NULL
      AND (
            ac.vehiculo_tracto_id = $idVehiculo
         OR ac.vehiculo_remolque_id = $idVehiculo
      )
";
$q = mysqli_query($conn, $sql);
if ($q) {
    while ($r = mysqli_fetch_assoc($q)) $eventos[] = $r;
}

/* ============================================================
   2. MANTENIMIENTOS
   ============================================================ */
$sql = "
    SELECT 
        m.fecha AS fecha,
        'Mantenimiento' AS tipo,
        CONCAT(mt.nombre, ' - ', m.descripcion) AS detalle
    FROM mantenimientos m
    JOIN mantenimiento_tipo mt ON m.tipo_id = mt.id
    WHERE m.vehiculo_id = $idVehiculo
      AND m.eliminado = 0
";
$q = mysqli_query($conn, $sql);
if ($q) {
    while ($r = mysqli_fetch_assoc($q)) $eventos[] = $r;
}

/* ============================================================
   3. PAPELETAS
   ============================================================ */
$sql = "
    SELECT 
        p.fecha_infraccion AS fecha,
        'Papeleta' AS tipo,
        CONCAT('Infracción ', i.codigo, ': ', i.descripcion) AS detalle
    FROM papeletas p
    JOIN infracciones i ON p.infraccion_id = i.id
    WHERE p.vehiculo_id = $idVehiculo
      AND p.eliminado = 0
";
$q = mysqli_query($conn, $sql);
if ($q) {
    while ($r = mysqli_fetch_assoc($q)) $eventos[] = $r;
}

/* ============================================================
   4. LLANTAS - INSTALACIONES
   ============================================================ */
$sql = "
    SELECT 
        li.fecha_instalacion AS fecha,
        'Llantas' AS tipo,
        CONCAT('Instalada llanta ', l.codigo, ' en posición ', lp.codigo) AS detalle
    FROM llanta_instalacion li
    JOIN llantas l ON li.llanta_id = l.id
    JOIN llanta_posicion lp ON li.posicion_id = lp.id
    WHERE li.vehiculo_id = $idVehiculo
";
$q = mysqli_query($conn, $sql);
if ($q) {
    while ($r = mysqli_fetch_assoc($q)) $eventos[] = $r;
}

/* ============================================================
   ORDENAR EVENTOS POR FECHA DESC
   ============================================================ */
usort($eventos, function($a, $b) {
    return strtotime($b['fecha']) - strtotime($a['fecha']);
});

/* ============================================================
   HTML
   ============================================================ */
ob_start();
?>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Tipo</th>
            <th>Detalle</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($eventos) > 0): ?>
            <?php foreach ($eventos as $ev): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($ev['fecha'])) ?></td>
                    <td><strong><?= $ev['tipo'] ?></strong></td>
                    <td><?= $ev['detalle'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" class="text-center text-muted">
                    No hay historial registrado para este vehículo.
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php
$html = ob_get_clean();

echo json_encode([
    'success' => true,
    'html' => $html
]);
exit;
