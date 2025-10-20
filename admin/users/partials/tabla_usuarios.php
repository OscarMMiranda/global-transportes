<?php if ($result_activos->num_rows > 0): ?>
  <div class="table-responsive">
    <table id="tablaActivos" class="table table-striped table-hover mb-0">
      <!-- <thead class="table-primary">
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Usuario</th>
          <th>Correo</th>
          <th>Rol</th>
          <th>Fecha Creación</th>
          <th class="text-center">Acciones</th>
        </tr>
      </thead> -->
      <tbody>
        <?php while ($u = $result_activos->fetch_assoc()): ?>
        <tr data-id="<?= $u['id'] ?>">
          <td><?= $u['id'] ?></td>
          <td class="nombre"><?= htmlspecialchars($u['nombre']) ?></td>
          <td class="apellido"><?= htmlspecialchars($u['apellido']) ?></td>
          <td><?= htmlspecialchars($u['usuario']) ?></td>
          <td class="correo"><?= htmlspecialchars($u['correo']) ?></td>
          <td class="rol"><?= htmlspecialchars(ucfirst($u['rol'])) ?></td>
          <td><?= htmlspecialchars($u['creado_en']) ?></td>
          <td class="text-center">
            <div class="btn-group btn-group-sm">
              <!-- Ver -->
              <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalUsuario<?= $u['id'] ?>">
                <i class="fa fa-eye"></i>
              </button>

              <!-- Editar -->
              <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditar<?= $u['id'] ?>">
                <i class="fa fa-pencil-alt"></i>
              </button>

              <!-- Eliminar -->
              <form action="eliminar_usuario.php" method="POST" class="d-inline" onsubmit="return confirm('⚠️ ¿Eliminar este usuario?');">
                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                <button type="submit" class="btn btn-outline-danger">
                  <i class="fa fa-trash"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>

        <?php require __DIR__ . '/../modals/modal_editar_usuario.php'; ?>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
<?php else: ?>
  <p class="text-muted">No hay usuarios activos.</p>
<?php endif; ?>