<?php
// archivo: componentes/encabezado.php
// propósito: encabezado visual del módulo Distritos por Zona
?>

<!-- Encabezado del módulo -->
<div class="d-flex justify-content-between align-items-center mb-3">

  <!-- Título principal -->
  <h1 class="h4 text-primary mb-0">
    <i class="fas fa-map-marker-alt me-2 text-secondary"></i>
    Distritos por Zona
  </h1>

  <!-- Botones de acción -->
  <div class="d-flex gap-2">
    <!-- Botón volver -->
    <a href="../mantenimiento.php" class="btn btn-outline-secondary btn-sm" title="Volver al panel de mantenimiento">
      <i class="fas fa-arrow-left me-1"></i> Volver
    </a>

    <!-- Botón agregar -->
    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalZona" title="Agregar nuevo distrito por zona">
      <i class="fas fa-plus me-1"></i> Nuevo
    </button>
  </div>

</div>