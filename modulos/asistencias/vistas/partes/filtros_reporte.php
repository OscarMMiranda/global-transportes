<?php
// archivo: modulos/asistencias/vistas/partes/filtros_reporte.php

if (!isset($empresas)) {
    $empresas = array();
}

$fecha_actual = date('Y-m-d');
?>

<div class="row g-3 mb-1">

    <div class="col-md-2">
        <label class="form-label">Fecha</label>
        <input 
            type="date" 
            id="filtro_fecha" 
            class="form-control" 
            value="<?php echo $fecha_actual; ?>"
        >
    </div>

	<!--  -- FILTRO EMPRESA -- -->
	<div class="col-md-3">
		<label class="form-label">Empresa</label>
		<select id="filtro_empresa" class="form-select form-select-lg">
			<option value="">Todas</option>

			<?php foreach ($empresas as $e): ?>
				<option value="<?php echo $e['id']; ?>">
					<?php echo htmlspecialchars($e['razon_social']); ?>
				</option>
			<?php endforeach; ?>
		</select>
    </div>

	<!-- BOTÓN BUSCAR -->
    <div class="col-md-2 d-flex align-items-end">
        <button type="button" class="btn btn-primary w-100" id="btnBuscarReporte">
            <i class="fa fa-search"></i> Buscar
        </button>
    </div>

	<!-- BOTÓN EXPORTAR A EXCEL -->
    <div class="col-md-2 d-flex align-items-end">
        <button type="button" class="btn btn-success w-100" id="btnExportarExcel">
            <i class="fa-solid fa-file-excel"></i> Descargar Excel
        </button>
    </div>

	<!-- BOTÓN VISTA COMPACTA / DETALLADA -->
	<div class="col-md-2 d-flex align-items-end">
		<button type="button" class="btn btn-secondary w-100" id="btnToggleVista">
			<i class="fa fa-list"></i> Vista Compacta
		</button>
	</div>

</div>
