<?php
    // archivo: /modulos/asistencias/vistas/reporte_diario.php

	require_once __DIR__ . '/../../../includes/config.php';
	$conn = getConnection();

	require_once __DIR__ . '/../../../includes/header_panel.php';
	require_once __DIR__ . '/../../../includes/componentes/topbar_global.php';
	require_once __DIR__ . '/../../../includes/componentes/navbar_global.php';

	require_once __DIR__ . '/../core/asistencia.func.php';
	require_once __DIR__ . '/../core/empresas.func.php';
	require_once __DIR__ . '/../core/conductores.func.php';
	require_once __DIR__ . '/../core/fechas.func.php';
	require_once __DIR__ . '/../core/helpers.func.php';

	$empresas = obtener_empresas($conn);
?>

<div class="card mt-1">
	 <!-- BOTÓN VOLVER -->
    <!-- <a href="/modulos/asistencias/vistas/index.php" class="btn btn-secondary mb-1">
        <i class="fa-solid fa-arrow-left"></i> Volver al módulo
    </a> -->

	<div class="card shadow-sm">
		<div class="card-header bg-dark text-white">
			<h5 class="mb-0">
				<i class="fa-solid fa-calendar-day"></i> Reporte Diario
			</h5>
		</div>
	

    	<div class="card-body">
        	
			<!-- FILTROS -->
        	<div class="row g-3 mb-3">
				<div class="col-md-3">
					<label class="form-label">Fecha</label>
					<input type="date" id="filtro_fecha" class="form-control" value="<?= date('Y-m-d') ?>">
				</div>
				<div class="col-md-3">
					<label class="form-label">Empresa</label>
					<select id="filtro_empresa" class="form-select form-select-lg">
						<option value="">Todas</option>
						<?php foreach ($empresas as $e): ?>
							<option value="<?= $e['id'] ?>"><?= $e['razon_social'] ?></option>
						<?php endforeach; ?>
					</select>
				</div>
 
				<div class="col-md-3 d-flex align-items-end">
					<button class="btn btn-primary w-100" id="btnBuscarReporte">
						<i class="fa-solid fa-search"></i> Buscar
					</button>
				</div>
			</div>

        	<!-- RESULTADOS -->
        	<div id="contenedorReporteDiario"></div>

    	</div>
	</div>
</div>

<!-- ============================================================
     DEPENDENCIAS NECESARIAS
     ============================================================ -->

<!-- 1) jQuery (OBLIGATORIO antes de tu JS) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- 2) Tu JS del reporte -->
<script src="/modulos/asistencias/js/reporte_diario.js"></script>



<?php include __DIR__ . '/../modales/modal_editar_asistencia.php'; ?>

<?php include __DIR__ . '/../modales/modal_historial.php'; ?>


<?php
require_once __DIR__ . '/../../../includes/footer.php';
?>