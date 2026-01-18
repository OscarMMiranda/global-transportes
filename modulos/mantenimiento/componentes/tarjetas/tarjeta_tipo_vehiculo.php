<?php
// archivo: /modulos/mantenimiento/componentes/tarjetas/tarjeta_tipo_vehiculo.php
// Usa $moduloConfig proveniente de index.php

$nombre      = isset($moduloConfig['nombre']) ? $moduloConfig['nombre'] : 'Tipo de Vehículos';
$descripcion = isset($moduloConfig['descripcion']) ? $moduloConfig['descripcion'] : 'Gestionar categorías de vehículos utilizados en operaciones.';
$icono       = isset($moduloConfig['icono']) ? $moduloConfig['icono'] : 'fa-truck';
$ruta        = isset($moduloConfig['ruta']) ? $moduloConfig['ruta'] : '/modulos/mantenimiento/tipo_vehiculo/index.php';
?>

<div class="col">
  <article class="card card-dashboard h-100 border-1 shadow-sm rounded-4">
    <div class="card-body d-flex flex-column">
      <h3 class="card-title h6">
        <i class="fa-solid <?= htmlspecialchars($icono) ?> me-2"></i>
        <?= htmlspecialchars($nombre) ?>
      </h3>
      <p class="card-text flex-fill">
        <?= htmlspecialchars($descripcion) ?>
      </p>

      <a 
        href="<?= htmlspecialchars($ruta) ?>"
        class="btn dashboard-btn btn-primary mt-3"
      >
        Actualizar
      </a>
    </div>
  </article>
</div>