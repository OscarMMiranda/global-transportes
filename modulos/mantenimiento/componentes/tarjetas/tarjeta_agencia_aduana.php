<?php
// archivo: /modulos/mantenimiento/componentes/tarjetas/tarjeta_agencia_aduana.php

$nombre      = isset($moduloConfig['nombre']) ? $moduloConfig['nombre'] : 'Agencia de Aduanas';
$descripcion = isset($moduloConfig['descripcion']) ? $moduloConfig['descripcion'] : 'Gestiona y administra las agencias de aduanas registradas.';
$icono       = isset($moduloConfig['icono']) ? $moduloConfig['icono'] : 'fa-warehouse';
$ruta        = isset($moduloConfig['ruta']) ? $moduloConfig['ruta'] : '/modulos/mantenimiento/agencia_aduana/index.php';
?>

<div class="col">
  <article class="card card-dashboard h-100 border-1 shadow-sm rounded-4">
    <div class="card-body d-flex flex-column">

      <h3 class="card-title h6 fw-bold text-primary mb-2">
        <i class="fas <?= htmlspecialchars($icono) ?> me-2 text-secondary"></i>
        <?= htmlspecialchars($nombre) ?>
      </h3>

      <p class="card-text text-muted flex-fill mb-3">
        <?= htmlspecialchars($descripcion) ?>
      </p>

      <a 
        href="<?= htmlspecialchars($ruta) ?>" 
        class="btn btn-sm btn-primary w-100 mt-auto"
      >
        <i class="fas fa-edit me-1"></i> Actualizar
      </a>

    </div>
  </article>
</div>