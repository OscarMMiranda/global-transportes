<?php
	// archivo: /modulos/conductores/componentes/tabs.php

	require_once realpath(__DIR__ . '/../controllers/conductores_controller.php');
	require_once realpath(__DIR__ . '/../../../includes/config.php');

	// 🔄 Cargar conexión
	$conn = getConnection();
	if (!$conn || !($conn instanceof mysqli)) {
  		echo '<div class="alert alert-danger">❌ No se pudo conectar a la base de datos.</div>';
  		error_log('❌ Conexión inválida en tabs.php');
  		return;
		}

	// 🔄 Cargar conductores
	try {
  		$activos = listarConductores($conn, false); // solo activos
		$inactivos = array_filter(listarConductores($conn, true), function ($c) {
    		return !$c['activo'];
			});
		} 
	catch (Exception $e) {
  		echo '<div class="alert alert-danger">❌ Error al cargar conductores.</div>';
  		error_log('❌ Error en tabs.php: ' . $e->getMessage());
  		$activos = $inactivos = [];
		}
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

<!-- 🗂️ Tabs de navegación -->
<ul class="nav nav-tabs" id="tabsConductores" role="tablist">
  	<li class="nav-item" role="presentation">
    	<button class="nav-link active" id="tab-activos"
        	data-bs-toggle="tab" data-bs-target="#panel-activos"
            type="button" role="tab" aria-controls="panel-activos" aria-selected="true">
      		<i class="fa-solid fa-user-check text-success me-2"></i> 
			Activos
    	</button>
  	</li>
  	<li class="nav-item" role="presentation">
    	<button class="nav-link" id="tab-inactivos"
            data-bs-toggle="tab" data-bs-target="#panel-inactivos"
            type="button" role="tab" aria-controls="panel-inactivos" aria-selected="false">
      		<i class="fa-solid fa-user-slash text-secondary me-2"></i> 
			Inactivos
    	</button>
  	</li>
</ul>

<!-- 📦 Paneles de contenido -->
<div class="tab-content mt-3" id="panelesConductores">
  <div class="tab-pane fade show active" id="panel-activos" role="tabpanel">
    <?php
      	$conductores = $activos;
	  	$tablaId = 'tblActivos';
      	include __DIR__ . '/tabla_conductores.php';
    ?>
  </div>
  <div class="tab-pane fade" id="panel-inactivos" role="tabpanel">
    <?php
      	$conductores = $inactivos;
	   	$tablaId = 'tblInactivos';
      	include __DIR__ . '/tabla_conductores.php';
    ?>
  </div>
</div>