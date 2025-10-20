<?php
session_start();

// 🔹 Mostrar errores solo en entorno de desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// 🔹 Conexión y controladores
require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();
require_once __DIR__ . '/controllers/tipo_documento_controller.php';

// 🔹 Solo admin
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
  header('Location: ../sistema/login.php');
  exit;
}

// 🔹 Acciones activar/desactivar
if (isset($_GET['eliminar'])) {
  $id = (int) $_GET['eliminar'];
  $stmt = $conn->prepare("UPDATE tipos_documento SET estado = 0 WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  header("Location: editar_tipo_documento.php?msg=Tipo desactivado");
  exit;
}

if (isset($_GET['activar'])) {
  $id = (int) $_GET['activar'];
  $stmt = $conn->prepare("UPDATE tipos_documento SET estado = 1 WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  header("Location: editar_tipo_documento.php?msg=Tipo activado");
  exit;
}

// 🔹 Procesar formulario
$msg = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $error = procesarTipoDocumento($_POST);
  if ($error === '') {
    header('Location: editar_tipo_documento.php?msg=Guardado');
    exit;
  }
}

$categorias = listarCategoriasDocumento();
$tipos = listarTiposDocumento();

$registro = isset($_GET['id'])
  ? obtenerTipoDocumento($_GET['id'])
  : ['id' => 0, 'categoria_id' => 0, 'nombre' => '', 'descripcion' => ''];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tipos de Documento</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <!-- DataTables -->
  <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body { background-color: #f8f9fa; }
    .card { border-radius: 12px; }
    th { white-space: nowrap; }
  </style>
</head>
<body class="p-4">

<div class="container-fluid">
  <div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
      <h4 class="fw-bold text-primary mb-0">
        <i class="fa-solid fa-file-lines me-2 text-secondary"></i> Tipos de Documento
      </h4>
      <a href="../mantenimiento.php" class="btn btn-outline-secondary btn-sm">
        <i class="fa fa-arrow-left me-1"></i> Volver
      </a>
    </div>

    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTipoDocumento">
          <i class="fa fa-plus me-1"></i> Nuevo Tipo de Documento
        </button>
      </div>

      <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php elseif (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
      <?php endif; ?>

      <table id="tablaTipos" class="table table-striped table-hover align-middle" style="width:100%">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Categoría</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($tipos as $t): ?>
            <tr>
              <td><?= $t['id'] ?></td>
              <td><?= htmlspecialchars($t['categoria']) ?></td>
              <td><?= htmlspecialchars($t['nombre']) ?></td>
              <td><?= htmlspecialchars($t['descripcion']) ?></td>
              <td>
                <?php if ($t['estado'] == 1): ?>
                  <span class="badge bg-success">Activo</span>
                <?php else: ?>
                  <span class="badge bg-secondary">Inactivo</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <a href="editar_tipo_documento.php?id=<?= $t['id'] ?>" 
                   class="btn btn-sm btn-outline-warning" 
                   title="Editar">
                   <i class="fa fa-pen"></i>
                </a>
                <?php if ($t['estado'] == 1): ?>
                  <button class="btn btn-sm btn-outline-danger" 
                          title="Desactivar" 
                          onclick="confirmarAccion('eliminar', <?= $t['id'] ?>)">
                    <i class="fa fa-ban"></i>
                  </button>
                <?php else: ?>
                  <button class="btn btn-sm btn-outline-success" 
                          title="Activar" 
                          onclick="confirmarAccion('activar', <?= $t['id'] ?>)">
                    <i class="fa fa-check"></i>
                  </button>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalTipoDocumento" tabindex="-1" aria-labelledby="modalTipoDocumentoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTipoDocumentoLabel">
          <?= $registro['id'] > 0 ? 'Editar Tipo de Documento' : 'Nuevo Tipo de Documento' ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" name="id" value="<?= $registro['id'] ?>">

        <div class="mb-3">
          <label class="form-label">Categoría</label>
          <select name="categoria_id" class="form-select" required>
            <option value="">– Selecciona –</option>
            <?php foreach ($categorias as $c): ?>
              <option value="<?= $c['id'] ?>" <?= $registro['categoria_id'] == $c['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($registro['nombre']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Descripción</label>
          <textarea name="descripcion" class="form-control" rows="3"><?= htmlspecialchars($registro['descripcion']) ?></textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
  $('#tablaTipos').DataTable({
    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
    pageLength: 10,
    responsive: true
  });
});

// Confirmación SweetAlert2
function confirmarAccion(accion, id) {
  const mensaje = accion === 'eliminar'
    ? '¿Deseas desactivar este tipo de documento?'
    : '¿Deseas activar este tipo de documento?';

  const url = `editar_tipo_documento.php?${accion}=${id}`;

  Swal.fire({
    title: 'Confirmar acción',
    text: mensaje,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, continuar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = url;
    }
  });
}

// Mostrar modal si viene con ?id=
<?php if (isset($_GET['id']) && $registro['id'] > 0): ?>
  const modal = new bootstrap.Modal(document.getElementById('modalTipoDocumento'));
  modal.show();
<?php endif; ?>
</script>
</body>
</html>
