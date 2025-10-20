<?php
	// archivo: /modulos/mantenimiento/tipo_vehiculo/vistas/view.php

	// 1) Modo depuración (solo en desarrollo)
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	ini_set('error_log', __DIR__ . '/error_log.txt');
?>

<?php
	// Encabezado visual y estructura HTML
	require_once __DIR__ . '/../../componentes/layout/header.php';
?>

<div class="container mt-1">
  	<h2><i class="fas fa-car me-2"></i> Tipos de Vehículo</h2>

  	<?php include __DIR__ . '/../componentes/mensajes_flash.php'; ?>

  	<!-- Pestañas Bootstrap -->
  	<ul class="nav nav-tabs mt-2" id="tabsVehiculo" role="tablist">
    	<li class="nav-item" role="presentation">
    	  	<button class="nav-link active" id="tab-activos" data-bs-toggle="tab" data-bs-target="#panel-activos" type="button" role="tab">
        		Activos
      		</button>
    	</li>
    	<li class="nav-item" role="presentation">
      		<button class="nav-link" id="tab-inactivos" data-bs-toggle="tab" data-bs-target="#panel-inactivos" type="button" role="tab">
        		Eliminados
      		</button>
    	</li>
  	</ul>

  <!-- Contenido de pestañas -->
  <div class="tab-content mt-3" id="contenidoVehiculo">
    <div class="tab-pane fade show active" id="panel-activos" role="tabpanel">
      <div id="contenedorActivos">
        <div class="text-muted text-center py-3">
          <i class="fas fa-spinner fa-spin me-2"></i> Cargando vehículos activos...
        </div>
      </div>
    </div>
    <div class="tab-pane fade" id="panel-inactivos" role="tabpanel">
      <div id="contenedorInactivos">
        <div class="text-muted text-center py-3">
          <i class="fas fa-spinner fa-spin me-2"></i> Cargando vehículos eliminados...
        </div>
      </div>
    </div>
  </div>

  <!-- Botón para abrir modal de creación -->
  <div class="text-end mt-4">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregar">
      <i class="fas fa-plus me-1"></i> Nuevo tipo de vehículo
    </button>
  </div>

  <!-- 🧩 Modales -->
  <?php include __DIR__ . '/../modales/modal_agregar.php'; ?>
  <?php include __DIR__ . '/../modales/modal_editar.php'; ?>
  <?php include __DIR__ . '/../modales/modal_ver.php'; ?>
</div>

<!-- JS para carga dinámica -->
<script src="/modulos/mantenimiento/tipo_vehiculo/js/tipo_vehiculo.js"></script>

<?php
// Pie de página y cierre de estructura HTML
require_once __DIR__ . '/../../componentes/layout/footer.php';
?>