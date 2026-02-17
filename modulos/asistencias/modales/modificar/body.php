<?php
// archivo: /modulos/asistencias/modales/modificar/body.php
?>

<div class="modal-body">

    <div id="alertaModificarAsistencia"></div>

    <form id="formModificarAsistencia">

    	<input type="hidden" id="asistencia_id">
    	<input type="hidden" id="empresa_id_hidden">
    	<input type="hidden" id="conductor_id_hidden">

    	<div class="row g-3">

    	<!-- Empresa y Conductor -->
    	<div class="col-md-6">
        	<label class="form-label fw-semibold small">
				Empresa
			</label>
        	<input 
				type="text" 
				id="empresa_id_edit" 
               	class="form-control bg-light" 
				disabled>
    	</div>

    	<div class="col-md-6">
        	<label class="form-label fw-semibold small">Conductor</label>
        	<input type="text" id="conductor_id_edit" 
            	class="form-control bg-light" disabled>
    	</div>

    	<!-- Tipo - Fecha - Feriado -->
    	<div class="col-md-3">
        	<label class="form-label fw-semibold small">Tipo</label>
        	<select id="codigo_tipo_edit" 
            	class="form-select form-select-lg"></select>
    	</div>

    	<div class="col-md-4">
        	<label class="form-label fw-semibold small">Fecha</label>
        	<input type="date" id="fecha_edit" 
            	class="form-control form-control-lg">
		</div>

		<div class="col-md-4">
			<label class="form-label fw-semibold small">Feriado</label>
			<input type="text" id="es_feriado_edit" 
				class="form-control bg-light form-control-lg" disabled>
		</div>

    <!-- Horas en una línea -->
    <div class="col-md-6">
        <label class="form-label fw-semibold small">Hora Entrada</label>
        <input type="time" id="hora_entrada_edit" 
               class="form-control">
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold small">Hora Salida</label>
        <input type="time" id="hora_salida_edit" 
               class="form-control">
    </div>

    <!-- Observaciones -->
    <div class="col-12">
        <label class="form-label fw-semibold small">Observación</label>
        <textarea id="observacion_edit" 
                  class="form-control" rows="3"></textarea>
    </div>

</div>



    </form>

</div>
