<?php
// archivo: /modulos/mantenimiento/componentes/tarjetas/tarjeta_valores_referenciales.php

$nombre      = isset($moduloConfig['nombre']) ? $moduloConfig['nombre'] : 'Valores Referenciales';
$descripcion = isset($moduloConfig['descripcion']) ? $moduloConfig['descripcion'] : 'Gestionar valores anuales por zona y tipo de carga.';
$icono       = isset($moduloConfig['icono']) ? $moduloConfig['icono'] : 'fa-money-bill-wave';
$ruta        = isset($moduloConfig['ruta']) ? $moduloConfig['ruta'] : '/modulos/mantenimiento/valores_referenciales/index.php';
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
      >
        Administrar Valores
      </a>

    </div>
  </article>
</div>