<?php
// archivo: /modulos/clientes/views/list.php
?>

<!-- Estilos -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/clientes.css">

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js" defer></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js" defer></script>
<script src="<?= BASE_URL ?>assets/js/clientes.js" defer></script>

<main class="container mt-4">
  <h2 class="text-center mb-4">
    <i class="fa-solid fa-address-book text-primary me-2 fs-2"></i>
    <span class="text-dark">Gestión de Clientes</span>
  </h2>

  <!-- Botonera -->
  <div class="d-flex flex-wrap justify-content-center gap-3 mb-4">
    <a href="?action=form" class="btn btn-success">
      <i class="fa-solid fa-user-plus me-1"></i> Registrar Cliente
    </a>
    <a href="<?= BASE_URL ?>modulos/erp_dashboard.php" class="btn btn-secondary">
      <i class="fa-solid fa-arrow-left me-1"></i> Volver al Dashboard
    </a>
    <a href="?action=trash" class="btn btn-danger">
      <i class="fa-solid fa-trash-can me-1"></i> Ver Eliminados
    </a>
  </div>

  <!-- Mensaje -->
  <?php if (!empty($_GET['msg'])): ?>
    <div class="alert alert-<?= $_GET['msg'] === 'ok' ? 'success' : 'danger' ?> text-center">
      <?= $_GET['msg'] === 'ok' ? '✅ Operación exitosa.' : '❌ Ocurrió un error.' ?>
    </div>
  <?php endif; ?>

  <!-- Tabla -->
  <div class="table-responsive shadow-sm">
    <table id="tablaClientes" class="table table-striped table-hover align-middle">
      <thead class="table-dark sticky-top">
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>RUC</th>
          <th>Dirección</th>
          <th>Distrito</th>
          <th class="text-center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($clientes)): ?>
          <?php foreach ($clientes as $c): ?>
            <tr>
              <td><?= $c['id'] ?></td>
              <td><?= htmlspecialchars($c['nombre'], ENT_QUOTES) ?></td>
              <td><?= htmlspecialchars($c['ruc'], ENT_QUOTES) ?></td>
              <td><?= htmlspecialchars($c['direccion'], ENT_QUOTES) ?></td>
              <td><?= htmlspecialchars($c['distrito'], ENT_QUOTES) ?></td>
              <td class="text-center">
                <a href="?action=form&id=<?= $c['id'] ?>" class="btn btn-sm btn-warning me-1" title="Editar">
                  <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <button type="button" class="btn btn-sm btn-info me-1 btn-ver" data-id="<?= $c['id'] ?>" title="Ver">
                  <i class="fa-solid fa-eye"></i>
                </button>
                <a href="?action=delete&id=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este cliente?');" title="Eliminar">
                  <i class="fa-solid fa-trash"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" class="text-center text-muted">No hay clientes activos.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

<!-- Modal -->
<div class="modal fade" id="modalVerCliente" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa-solid fa-eye me-2"></i> Detalle del Cliente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- AJAX content -->
      </div>
    </div>
  </div>
</div>