<?php
// archivo: /modulos/conductores/componentes/botones_conductor.php
// Requiere $c definido (conductor actual)
?>

<div class="btn-group btn-group-sm acciones-conductor" role="group">

    <!-- Ver -->
    <button 
        type="button"
        class="btn btn-outline-info btn-view"
        data-id="<?= $c['id'] ?>"
        title="Ver conductor"
        aria-label="Ver conductor <?= htmlspecialchars($c['nombres'] . ' ' . $c['apellidos']) ?>"
    >
        <i class="fa-solid fa-eye"></i>
    </button>

    <!-- Editar -->
    <button 
        type="button"
        class="btn btn-outline-primary btn-edit"
        data-id="<?= $c['id'] ?>"
        title="Editar conductor"
        aria-label="Editar conductor <?= htmlspecialchars($c['nombres'] . ' ' . $c['apellidos']) ?>"
    >
        <i class="fa-solid fa-pen-to-square"></i>
    </button>

    <?php if (!empty($c['activo']) && (int)$c['activo'] === 1): ?>

        <!-- Desactivar -->
        <button 
            type="button"
            class="btn btn-outline-warning btn-soft-delete"
            data-id="<?= $c['id'] ?>"
            title="Desactivar conductor"
            aria-label="Desactivar conductor <?= htmlspecialchars($c['nombres'] . ' ' . $c['apellidos']) ?>"
        >
            <i class="fa-solid fa-ban"></i>
        </button>

    <?php else: ?>

        <!-- Restaurar -->
        <button 
            type="button"
            class="btn btn-outline-success btn-restore"
            data-id="<?= $c['id'] ?>"
            title="Restaurar conductor"
            aria-label="Restaurar conductor <?= htmlspecialchars($c['nombres'] . ' ' . $c['apellidos']) ?>"
        >
            <i class="fa-solid fa-rotate-left"></i>
        </button>

        <!-- Eliminar definitivo -->
        <button 
            type="button"
            class="btn btn-outline-danger btn-delete"
            data-id="<?= $c['id'] ?>"
            title="Eliminar definitivamente"
            aria-label="Eliminar definitivamente a <?= htmlspecialchars($c['nombres'] . ' ' . $c['apellidos']) ?>"
        >
            <i class="fa-solid fa-trash"></i>
        </button>

    <?php endif; ?>

</div>