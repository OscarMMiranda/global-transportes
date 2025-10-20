<?php
	// 	archivo	: 	/admin/users/partials/form_usuario.php
	// Requiere: $usuario (array), $roles (mysqli_result), $modo ('crear' o 'editar'), $mensaje (string opcional)

	$modo = isset($modo) ? $modo : 'editar';
	$usuario = isset($usuario) ? $usuario : ['nombre'=>'','apellido'=>'','correo'=>'','rol'=>''];
?>

<div class="card shadow-sm border-0">
  	<div class="card-body">
    	<h4 class="card-title text-center mb-4">
      		<i class="fas fa-user-<?= $modo === 'crear' ? 'plus' : 'edit' ?> me-2 text-primary"></i>
      		<?= $modo === 'crear' ? 'Crear Usuario' : 'Editar Usuario' ?>
    	</h4>

    <?php if (!empty($mensaje)): ?>
      <div class="alert <?= strpos($mensaje,'✅')!==false ? 'alert-success' : 'alert-danger' ?> small">
        <i class="fas <?= strpos($mensaje,'✅')!==false ? 'fa-check-circle' : 'fa-exclamation-triangle' ?> me-2"></i>
        <?= htmlspecialchars($mensaje) ?>
      </div>
    <?php endif; ?>

    <form method="post" onsubmit="return confirmarActualizacion();" class="small">
      <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" id="nombre" name="nombre" class="form-control"
               value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
      </div>

      <div class="mb-3">
        <label for="apellido" class="form-label">Apellido</label>
        <input type="text" id="apellido" name="apellido" class="form-control"
               value="<?= htmlspecialchars($usuario['apellido']) ?>" required>
      </div>

      <div class="mb-3">
        <label for="correo" class="form-label">Correo</label>
        <input type="email" id="correo" name="correo" class="form-control"
               value="<?= htmlspecialchars($usuario['correo']) ?>" required>
      </div>

      <div class="mb-4">
        <label for="rol" class="form-label">Rol</label>
        <select id="rol" name="rol" class="form-select" required>
          <?php while ($r = $roles->fetch_assoc()): ?>
            <option value="<?= $r['id'] ?>" <?= $usuario['rol']==$r['id'] ? 'selected' : '' ?>>
              <?= ucfirst(htmlspecialchars($r['nombre'])) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save me-1"></i> <?= $modo === 'crear' ? 'Crear' : 'Actualizar' ?>
        </button>
        <a href="/admin/users/users.php" class="btn btn-outline-secondary">
          <i class="fas fa-arrow-left me-1"></i> Volver a la lista
        </a>
      </div>
    </form>
  </div>
</div>

<script>
  function confirmarActualizacion() {
    return confirm("⚠️ ¿Seguro que quieres <?= $modo === 'crear' ? 'crear' : 'actualizar' ?> este usuario?");
  }
</script>