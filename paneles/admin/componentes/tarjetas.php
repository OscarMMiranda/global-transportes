<?php
// archivo: /paneles/admin/componentes/tarjetas.php

// Seguridad mínima
if (!isset($tarjetas) || !is_array($tarjetas)) {
    echo "<div class='alert alert-danger'>Error: No se encontraron tarjetas para mostrar.</div>";
    return;
}
?>

<div class="row g-4">

    <?php foreach ($tarjetas as $t): ?>
        <div class="col-sm-6 col-lg-3">
            <div class="card h-100 shadow-sm text-center border-0">

                <div class="card-body">

                    <!-- Icono -->
                    <i class="fa <?= htmlspecialchars($t['icono']) ?> fa-2x text-primary mb-3"></i>

                    <!-- Título -->
                    <h5 class="card-title">
                        <?= htmlspecialchars($t['titulo']) ?>
                    </h5>

                    <!-- Descripción -->
                    <p class="text-muted small">
                        <?= htmlspecialchars($t['descripcion']) ?>
                    </p>

                    <!-- Botón -->
                    <a href="<?= htmlspecialchars($t['ruta']) ?>" class="btn btn-primary">
                        Ir
                    </a>

                </div>

            </div>
        </div>
    <?php endforeach; ?>

</div>