<?php
// archivo: /modulos/vehiculos/componentes/tabs.php
?>

<div class="d-flex justify-content-between align-items-center mb-2">

  <!-- 🔙 Botón de retorno -->
  <a href="/modulos/erp_dashboard.php" class="btn btn-secondary">
    <i class="fa-solid fa-arrow-left me-2"></i>
    Volver al módulo anterior
  </a>

  <!-- ➕ Botón de nuevo vehículo (CORREGIDO) -->
  <button type="button" class="btn btn-success btn-nuevo">
    <i class="fa-solid fa-plus me-1"></i>
    Nuevo vehículo
  </button>

</div>

<!-- Mensajes flash -->
<?php include __DIR__ . '/mensajes_flash.php'; ?>

<!-- 🗂️ Tabs de navegación -->
<ul class="nav nav-tabs" id="tabsVehiculos" role="tablist">

  <li class="nav-item" role="presentation">
    <button class="nav-link active"
            id="tab-activos"
            data-bs-toggle="tab"
            data-bs-target="#panel-activos"
            type="button"
            role="tab"
            aria-controls="panel-activos"
            aria-selected="true">
      <i class="fa-solid fa-car-side text-success me-2"></i>
      <span class="fw-bold">Activos</span>
    </button>
  </li>

  <li class="nav-item" role="presentation">
    <button class="nav-link"
            id="tab-inactivos"
            data-bs-toggle="tab"
            data-bs-target="#panel-inactivos"
            type="button"
            role="tab"
            aria-controls="panel-inactivos"
            aria-selected="false">
      <i class="fa-solid fa-car-burst text-secondary me-2"></i>
      <span class="fw-bold">Inactivos</span>
    </button>
  </li>

</ul>

<!-- 📦 Paneles de contenido -->
<div class="tab-content mt-3" id="panelesVehiculos">

  <!-- TAB ACTIVOS -->
  <div class="tab-pane fade show active" id="panel-activos" role="tabpanel">
    <?php
      $tablaId = 'tblActivosVehiculos';
      include __DIR__ . '/tabla_vehiculos.php';
    ?>
  </div>

  <!-- TAB INACTIVOS -->
  <div class="tab-pane fade" id="panel-inactivos" role="tabpanel">
    <?php
      $tablaId = 'tblInactivosVehiculos';
      include __DIR__ . '/tabla_vehiculos.php';
    ?>
  </div>

</div>