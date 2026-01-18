<?php
// archivo: /modulos/mantenimiento/componentes/tarjetas/tarjeta_tipo_lugar.php

$nombre      = isset($moduloConfig['nombre']) ? $moduloConfig['nombre'] : 'Tipo de Lugar';
$descripcion = isset($moduloConfig['descripcion']) ? $moduloConfig['descripcion'] : 'Administrar tipos de lugares: origen, destino, tránsito, etc.';
$icono       = isset($moduloConfig['icono']) ? $moduloConfig['icono'] : 'fa-location-dot';
$ruta        = isset($moduloConfig['ruta']) ? $moduloConfig['ruta'] : '/modulos/mantenimiento/tipo_lugares/index.php';
?>

<div class="col">
  <article class="card card-dashboard h-100 border-1 shadow-sm rounded-4">
    <div class="card-body d-flex flex-column">

      <h3 class="card-title h6">
        <i class="fas <?= htmlspecialchars($icono) ?> me-2 text-primary"></i>
        <?= htmlspecialchars($nombre) ?>
      </h3>

      <p class="card-text flex-fill">
        <?= htmlspecialchars($descripcion) ?>
      </p>

      <a 
        href="<?= htmlspecialchars($ruta) ?>" 
        class="btn dashboard-btn btn-primary mt-3"
        aria-label="<?= htmlspecialchars($nombre) ?>"
      >
        Gestionar
      </a>

    </div>
  </article>
</div>
