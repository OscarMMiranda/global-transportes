<?php
// archivo: /modulos/conductores/componentes/botones_conductor.php
// Requiere $c definido (conductor actual)
?>

<div class="btn-group btn-group-sm" role="group" aria-label="Botones de acción del conductor">

  <!-- Ver -->
  <button 
    type="button" 
    class="btn btn-info btn-view" 
    data-id="<?= $c['id'] ?>" 
    title="Ver conductor"
    aria-label="Ver conductor <?= htmlspecialchars($c['nombres'] . ' ' . $c['apellidos']) ?>"
  >
    <i class="fa-solid fa-eye"></i>
  </button>

  <!-- Editar -->
  <button 
    type="button" 
    class="btn btn-primary btn-edit" 
    data-id="<?= $c['id'] ?>" 
    title="Editar conductor"
    aria-label="Editar conductor <?= htmlspecialchars($c['nombres'] . ' ' . $c['apellidos']) ?>"
  >
    <i class="fa-solid fa-pen-to-square"></i>
  </button>

  <?php if (!empty($c['activo']) && (int)$c['activo'] === 1): ?>
    <!-- Eliminar -->
    <button 
      type="button" 
      class="btn btn-danger btn-delete" 
      data-id="<?= $c['id'] ?>" 
      title="Eliminar conductor"
      aria-label="Eliminar conductor <?= htmlspecialchars($c['nombres'] . ' ' . $c['apellidos']) ?>"
    >
      <i class="fa-solid fa-trash-can"></i>
    </button>
  <?php else: ?>
    <!-- Restaurar -->
    <button 
      type="button" 
      class="btn btn-success btn-restore" 
      data-id="<?= $c['id'] ?>" 
      title="Restaurar conductor"
      aria-label="Restaurar conductor <?= htmlspecialchars($c['nombres'] . ' ' . $c['apellidos']) ?>"
    >
      <i class="fa-solid fa-rotate-left"></i>
    </button>
  <?php endif; ?>

</div>