<?php
if (!isset($tarjetas) || !is_array($tarjetas)) {
    echo "<div class='alert alert-danger'>No hay tarjetas para mostrar.</div>";
    return;
}
?>

<div class="row g-4">

<?php foreach ($tarjetas as $t): ?>
    <div class="col-sm-6 col-lg-4">
        <div class="card h-100 shadow-sm text-center border-0">

            <div class="card-body">

                <i class="fa <?= htmlspecialchars($t['icono']) ?> fa-2x text-danger mb-3"></i>

                <h5 class="card-title"><?= htmlspecialchars($t['titulo']) ?></h5>

                <p class="text-muted small"><?= htmlspecialchars($t['descripcion']) ?></p>

                <a href="<?= htmlspecialchars($t['ruta']) ?>" class="btn btn-danger text-white">
                    Ir
                </a>

            </div>

        </div>
    </div>
<?php endforeach; ?>

</div>