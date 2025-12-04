<?php
// archivo: /modulos/conductores/componentes/tabs.php
?>

<div class="d-flex justify-content-between align-items-center mb-2">
  <!-- 🔙 Botón de retorno -->
  <a href="/modulos/erp_dashboard.php" class="btn btn-secondary">
    <i class="fa-solid fa-arrow-left me-2"></i> 
    Volver al módulo anterior
  </a>

  <!-- ➕ Botón de nuevo conductor -->  
  <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalConductor">
    <i class="fa-solid fa-plus me-1"></i> 
    Nuevo conductor
  </button>
</div>

<!-- Mensajes flash -->
<?php include __DIR__ . '/../../includes/mensajes_flash.php'; ?>

<!-- 🗂️ Tabs de navegación -->
<ul class="nav nav-tabs" id="tabsConductores" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="tab-activos"
        data-bs-toggle="tab" data-bs-target="#panel-activos"
        type="button" role="tab" aria-controls="panel-activos" aria-selected="true"
        aria-label="Ver conductores activos">
      <i class="fa-solid fa-user-check text-success me-2"></i> 
      <span class="fw-bold">Activos</span>
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="tab-inactivos"
        data-bs-toggle="tab" data-bs-target="#panel-inactivos"
        type="button" role="tab" aria-controls="panel-inactivos" aria-selected="false"
        aria-label="Ver conductores inactivos">
      <i class="fa-solid fa-user-slash text-secondary me-2"></i> 
      <span class="fw-bold">Inactivos</span>
    </button>
  </li>
</ul>

<!-- 📦 Paneles de contenido -->
<div class="tab-content mt-3" id="panelesConductores">
  <div class="tab-pane fade show active" id="panel-activos" role="tabpanel">
    <?php
      $tablaId = 'tblActivos';
      include __DIR__ . '/tabla_conductores.php';
    ?>
  </div>
  <div class="tab-pane fade" id="panel-inactivos" role="tabpanel">
    <?php
      $tablaId = 'tblInactivos';
      include __DIR__ . '/tabla_conductores.php';
    ?>
  </div>
</div>