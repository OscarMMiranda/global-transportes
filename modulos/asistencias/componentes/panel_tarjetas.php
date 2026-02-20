<?php
	// archivo: /modulos/asistencias/componentes/panel_tarjetas.php
?>

<div class="panel-grid">

	<!-- Registrar asistencia -->
    <button class="card card-btn btnRegistrarAsistencia">
		<div class="card-body text-center">
			<h3 class="card-title mb-1">
				<i class="fa-solid fa-plus-circle"></i> Registrar asistencia
			</h3>
			<small class="text-muted">Nuevo registro diario</small>
		</div>
	</button>

    <!-- Modificar asistencia (flujo moderno: ir a la tabla) -->
    <button class="card card-btn"
            onclick="location.href='/modulos/asistencias/vistas/modificar_asistencia.php'">
        <div class="card-body text-center">
            <h3 class="card-title mb-1">
                <i class="fa-solid fa-pen-to-square"></i> Modificar asistencia
            </h3>
            <small class="text-muted">Editar registros existentes</small>
        </div>
    </button>

    <!-- Reporte diario -->
    <button class="card card-btn"
            onclick="location.href='/modulos/asistencias/vistas/reporte_diario.php'">
        <div class="card-body text-center">
            <h3 class="card-title mb-1">
                <i class="fa-solid fa-calendar-day"></i> Reporte diario
            </h3>
            <small class="text-muted">Resumen por fecha</small>
        </div>
    </button>

    <!-- Reporte mensual -->
    <button class="card card-btn"
            onclick="location.href='/modulos/asistencias/reporte_mensual/index.php'">
        <div class="card-body text-center">
            <h3 class="card-title mb-1">
                <i class="fa-solid fa-table"></i> Reporte mensual
            </h3>
            <small class="text-muted">Matriz por conductor</small>
        </div>
    </button>

    <!-- Vacaciones -->
    <button class="card card-btn" id="btnAbrirVacaciones">
        <div class="card-body text-center">
            <h3 class="card-title mb-1">
                <i class="fa-solid fa-plane-departure"></i> Vacaciones
            </h3>
            <small class="text-muted">Registrar rango</small>
        </div>
    </button>

    <!-- Permisos -->
    <button class="card card-btn" id="btnAbrirPermisos">
        <div class="card-body text-center">
            <h3 class="card-title mb-1">
                <i class="fa-solid fa-id-card"></i> Permisos
            </h3>
            <small class="text-muted">Con goce / sin goce</small>
        </div>
    </button>

</div>
