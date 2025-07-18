<?php
// Variables: $clientesEliminados, $_GET['msg']
?>

<main class="container mt-4">
  <h2 class="titulo-pagina mb-4">Clientes Eliminados</h2>


  <div class="mb-3">
  <a href="?action=list" class="btn btn-outline-primary">
    <i class="fas fa-arrow-left me-1"></i>
    Volver al listado de clientes
  </a>
</div>


  <?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-<?= $_GET['msg']==='restored'?'success':'danger' ?> mb-3">
      <?= $_GET['msg']==='restored'
         ? 'Cliente restaurado correctamente.'
         : 'No se pudo restaurar el cliente.' ?>
    </div>
  <?php endif; ?>

  <div class="table-responsive">
    <table class="table table-striped">
      <thead class="table-dark">
        <tr>
          <th>ID</th><th>Nombre</th><th>RUC</th><th>Dirección</th>
          <th>Correo</th><th>Teléfono</th><th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($clientesEliminados)): ?>
          <?php foreach ($clientesEliminados as $c): ?>
            <tr>
              <td><?= $c['id'] ?></td>
              <td><?= htmlspecialchars($c['nombre'],ENT_QUOTES) ?></td>
              <td><?= htmlspecialchars($c['ruc'],ENT_QUOTES) ?></td>
              <td><?= htmlspecialchars($c['direccion'],ENT_QUOTES) ?></td>
              <td><?= htmlspecialchars($c['correo'],ENT_QUOTES) ?></td>
              <td><?= htmlspecialchars($c['telefono'],ENT_QUOTES) ?></td>
              <td>
                <a href="?action=restore&id=<?= $c['id'] ?>"
                   class="btn btn-success btn-lg w-100"
                   onclick="return confirm('Restaurar este cliente?')">
                   <i class="fas fa-undo"></i> Restaurar
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" class="text-center">No hay clientes eliminados.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>
