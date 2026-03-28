<?php
// archivo: /paneles/admin/componentes/tarjetas.php

// Seguridad mínima y validación estricta
if (!isset($tarjetas) || !is_array($tarjetas) || count($tarjetas) === 0) {
    echo "<div class='alert alert-danger'>Error: No se encontraron tarjetas para mostrar.</div>";
    return;
}
?>

<div class="row g-4">

    <?php foreach ($tarjetas as $t): ?>

        <?php
        // Validación defensiva por tarjeta (evita warnings en PHP 5.6)
        $titulo      = isset($t['titulo']) ? htmlspecialchars($t['titulo']) : 'Sin título';
        $icono       = isset($t['icono']) ? htmlspecialchars($t['icono']) : 'fa-question-circle';
        $descripcion = isset($t['descripcion']) ? htmlspecialchars($t['descripcion']) : '';
        $ruta        = isset($t['ruta']) ? htmlspecialchars($t['ruta']) : '#';
        ?>

        <div class="col-sm-6 col-lg-3">
            <div class="card h-100 shadow-sm text-center border-0">

                <div class="card-body">

                    <!-- Icono -->
                    <i class="fa <?php echo $icono; ?> fa-2x text-primary mb-3"></i>

                    <!-- Título -->
                    <h5 class="card-title">
                        <?php echo $titulo; ?>
                    </h5>

                    <!-- Descripción -->
                    <p class="text-muted small">
                        <?php echo $descripcion; ?>
                    </p>

                    <!-- Botón -->
                    <a href="<?php echo $ruta; ?>" class="btn btn-primary">
                        Ir
                    </a>

                </div>

            </div>
        </div>

    <?php endforeach; ?>

</div>
