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
$sql = "SELECT * FROM entidades WHERE id = $id LIMIT 1";
$res = $conn->query($sql);

if (!$res || $res->num_rows === 0) {
    echo "<div class='modal-body'><div class='alert alert-warning'>Entidad no encontrada.</div></div>";
    return;
}

$entidad = $res->fetch_assoc();

// 4) Mostrar datos
?>
<div class="modal-header bg-info" style="color:#fff;">
  <h4 class="modal-title"><i class="fa fa-eye"></i> Detalle de entidad</h4>
</div>
<div class="modal-body">
  <table class="table table-bordered table-striped">
    <tr><th>ID</th><td><?= $entidad['id'] ?></td></tr>
    <tr><th>Nombre</th><td><?= htmlspecialchars($entidad['nombre']) ?></td></tr>
    <tr><th>Tipo</th><td><?= htmlspecialchars($entidad['tipo_id']) ?></td></tr>
    <tr><th>Departamento</th><td><?= htmlspecialchars($entidad['departamento_id']) ?></td></tr>
    <tr><th>Provincia</th><td><?= htmlspecialchars($entidad['provincia_id']) ?></td></tr>
    <tr><th>Distrito</th><td><?= htmlspecialchars($entidad['distrito_id']) ?></td></tr>
    <tr><th>Estado</th><td><?= $entidad['estado'] === 'activo' ? 'Activo' : 'Inactivo' ?></td></tr>
  </table>
</div>
<div class="modal-footer">
  <button class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
</div>