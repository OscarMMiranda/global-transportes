<?php
	// archivo: /modulos/asistencias/vistas/partes/filtros.php
?>	

<div class="filtros-box">
	<div class="row g-2">

<div class="col-md-3">
    <label class="form-label">Empresa</label>
    <select id="f_empresa" class="form-select">
        <option value="">Seleccione...</option>
        <?php foreach ($empresas as $e): ?>
            <option value="<?= $e['id'] ?>">
                <?= $e['razon_social'] ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="col-md-3">
    <label class="form-label">Conductor</label>
    <select id="f_conductor" class="form-select" disabled>
        <option value="">Seleccione empresa primero...</option>
    </select>
</div>


		<!-- PERIODO -->
		<div class="col-md-2">
			<label class="form-label">Periodo</label>
			<select id="f_periodo" class="form-select">
				<option value="hoy">Hoy</option>
				<option value="ayer">Ayer</option>
				<option value="semana">Esta semana</option>
				<option value="mes">Este mes</option>
				<option value="rango">Rango personalizado</option>
			</select>
		</div>

		<!-- RANGO FECHAS -->
		<div class="col-md-2">
			<label class="form-label">Desde</label>
			<input type="date" id="f_desde" class="form-control" disabled>
		</div>

		<div class="col-md-2">
			<label class="form-label">Hasta</label>
			<input type="date" id="f_hasta" class="form-control" disabled>
		</div>

		<!-- TIPO -->
		<div class="col-md-4">
			<label class="form-label">Tipo</label>
			<select id="f_tipo" class="form-select">
    			<option value="">Todos</option>
			</select>
		</div>
	</div>

	<div class="mt-0 text-end">
		<button class="btn btn-primary" id="btnBuscar">
			<i class="fa-solid fa-magnifying-glass"></i> Buscar
		</button>
	</div>

</div>
