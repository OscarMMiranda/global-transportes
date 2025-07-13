<?php
// /modulos/clientes/ver_cliente.php
require_once '../../includes/conexion.php';

// Toma y valida ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo '<p class="text-danger">ID de cliente inválido.</p>';
    exit;
}

// Consulta con JOIN para datos completos
$sql = "
  SELECT
    c.*,
    d.nombre   AS departamento,
    p.nombre   AS provincia,
    dis.nombre AS distrito
  FROM clientes c
  LEFT JOIN departamentos d ON c.departamento_id = d.id
  LEFT JOIN provincias  p ON c.provincia_id    = p.id
  LEFT JOIN distritos    dis ON c.distrito_id    = dis.id
  WHERE c.id = ?
";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (! $res || mysqli_num_rows($res) === 0) {
    echo '<p class="text-danger">Cliente no encontrado.</p>';
    exit;
}

$c = mysqli_fetch_assoc($res);
?>

<dl class="row">
  <dt class="col-sm-4">ID</dt>
  <dd class="col-sm-8"><?= $c['id'] ?></dd>

  <dt class="col-sm-4">Nombre</dt>
  <dd class="col-sm-8"><?= htmlspecialchars($c['nombre']) ?></dd>

  <dt class="col-sm-4">RUC</dt>
  <dd class="col-sm-8"><?= htmlspecialchars($c['ruc']) ?></dd>

  <dt class="col-sm-4">Dirección</dt>
  <dd class="col-sm-8"><?= htmlspecialchars($c['direccion']) ?></dd>

  <dt class="col-sm-4">Teléfono</dt>
  <dd class="col-sm-8"><?= htmlspecialchars($c['telefono']) ?></dd>

  <dt class="col-sm-4">Correo</dt>
  <dd class="col-sm-8"><?= htmlspecialchars($c['correo']) ?></dd>

  <dt class="col-sm-4">Departamento</dt>
  <dd class="col-sm-8"><?= htmlspecialchars($c['departamento']) ?></dd>

  <dt class="col-sm-4">Provincia</dt>
  <dd class="col-sm-8"><?= htmlspecialchars($c['provincia']) ?></dd>

  <dt class="col-sm-4">Distrito</dt>
  <dd class="col-sm-8"><?= htmlspecialchars($c['distrito']) ?></dd>

  <dt class="col-sm-4">Estado</dt>
  <dd class="col-sm-8"><?= htmlspecialchars($c['estado']) ?></dd>
</dl>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
