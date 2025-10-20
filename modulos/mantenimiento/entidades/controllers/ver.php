<?php
// archivo: ver.php — respuesta HTML para el modal de visualización

error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1) Validar ID recibido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='modal-body'><div class='alert alert-danger'>ID inválido.</div></div>";
    return;
}

$id = intval($_GET['id']);

// 2) Cargar conexión
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

if (!is_object($conn) || get_class($conn) !== 'mysqli') {
    echo "<div class='modal-body'><div class='alert alert-danger'>Error de conexión.</div></div>";
    return;
}

// 3) Consultar entidad
$sql = "SELECT 
            e.id, 
            e.nombre, 
            e.ruc, 
            e.direccion, 
            e.estado,
            t.nombre AS tipo_nombre,
            dpto.nombre AS departamento_nombre,
            prov.nombre AS provincia_nombre,
            dist.nombre AS distrito_nombre
        FROM entidades e
        LEFT JOIN tipo_lugares t ON e.tipo_id = t.id
        LEFT JOIN distritos dist ON e.distrito_id = dist.id
        LEFT JOIN provincias prov ON dist.provincia_id = prov.id
        LEFT JOIN departamentos dpto ON prov.departamento_id = dpto.id
        WHERE e.id = $id
        LIMIT 1";

$res = $conn->query($sql);
if (!$res || $res->num_rows === 0) {
    echo "<div class='modal-body'><div class='alert alert-warning'>Entidad no encontrada.</div></div>";
    return;
}

$row = $res->fetch_assoc();
?>

<div class="modal-header bg-info text-white">
  <h4 class="modal-title"><i class="fa fa-eye"></i> Detalle de entidad</h4>
</div>
<div class="modal-body">
  <table class="table table-bordered table-striped">
    <tr><th>Nombre</th><td><?= htmlspecialchars($row['nombre']) ?></td></tr>
    <tr><th>RUC</th><td><?= htmlspecialchars($row['ruc']) ?></td></tr>
    <tr><th>Dirección</th><td><?= htmlspecialchars($row['direccion']) ?></td></tr>
    <tr><th>Tipo</th><td><?= htmlspecialchars($row['tipo_nombre']) ?></td></tr>
    <tr><th>Departamento</th><td><?= htmlspecialchars($row['departamento_nombre']) ?></td></tr>
    <tr><th>Provincia</th><td><?= htmlspecialchars($row['provincia_nombre']) ?></td></tr>
    <tr><th>Distrito</th><td><?= htmlspecialchars($row['distrito_nombre']) ?></td></tr>
    <tr><th>Estado</th><td><span class="label label-<?= $row['estado'] === 'activo' ? 'success' : 'danger' ?>"><?= ucfirst($row['estado']) ?></span></td></tr>
  </table>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
</div>