<?php
	// 	archivo	:	/modulos/mantenimiento/tipo_documento/modales/modal_tipo_documento.php

	$registro = array_merge([
  		'id' => 0,
  		'categoria_id' => 0,
  		'nombre' => '',
  		'descripcion' => '',
  		'duracion_meses' => '',
  		'requiere_inicio' => 0,
  		'requiere_vencimiento' => 0,
  		'requiere_archivo' => 0,
  		'validacion_externa' => 0,
  		'codigo_interno' => '',
  		'color_etiqueta' => '#ffffff',
  		'icono' => '',
  		'grupo' => ''
		], isset($registro) && is_array($registro) ? $registro : []);

	$modo_ver = isset($_GET['ver']) && $_GET['ver'] == 1;
	$action = $registro['id'] > 0 ? 'ajax/editar.php' : 'ajax/agregar.php';
?>

<div class="modal fade" id="modalTipoDocumento" tabindex="-1" aria-labelledby="modalTipoDocumentoLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
    	<form method="post" action="<?= $modo_ver ? '#' : $action ?>" class="modal-content <?= $modo_ver ? 'modo-ver' : '' ?>" autocomplete="off">
      		<div class="modal-header bg-primary text-white">
        		<h5 class="modal-title" id="modalTipoDocumentoLabel">
          			<?= $modo_ver ? 'Ver Tipo de Documento' : ($registro['id'] > 0 ? 'Editar Tipo de Documento' : 'Nuevo Tipo de Documento') ?>
        		</h5>
        		<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      		</div>

      		<div class="modal-body">
        		<input type="hidden" name="id" value="<?= (int)$registro['id'] ?>">

   			<div class="row mb-3">
				<!-- Categoría -->
  				<div class="col-md-3">
    				<label for="categoria_id" class="form-label">Categoría <span class="text-danger">*</span></label>
    				<select name="categoria_id" id="categoria_id" class="form-select" required <?= $modo_ver ? 'disabled' : '' ?>>
      					<option value="">– Selecciona –</option>
      					<?php foreach ($categorias as $c): ?>
        					<option value="<?= $c['id'] ?>" <?= $registro['categoria_id'] == $c['id'] ? 'selected' : '' ?>>
          						<?= htmlspecialchars($c['nombre']) ?>
        					</option>
      					<?php endforeach; ?>
    				</select>
  				</div>
  				<!-- Color -->
  				<div class="col-md-3">
    				<label for="color_etiqueta" class="form-label">Color de etiqueta</label>
    				<input type="color" name="color_etiqueta" id="color_etiqueta"
           				class="form-control form-control-color"
           				value="<?= htmlspecialchars($registro['color_etiqueta']) ?>" <?= $modo_ver ? 'disabled' : '' ?>>
  				</div>
  				<!-- Nombre -->
  				<div class="col-md-6">
    				<label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
    				<input type="text" name="nombre" id="nombre" class="form-control"
           				value="<?= htmlspecialchars($registro['nombre']) ?>" required maxlength="100" <?= $modo_ver ? 'readonly' : '' ?>>
  				</div>
			</div>


        		<!-- Descripción -->
        		<div class="mb-3">
          			<label for="descripcion" class="form-label">Descripción</label>
          			<textarea name="descripcion" id="descripcion" class="form-control" rows="2" maxlength="255" <?= $modo_ver ? 'readonly' : '' ?>><?= htmlspecialchars($registro['descripcion']) ?></textarea>
        		</div>

        		<!-- Duración -->
        		<div class="mb-3">
          			<label for="duracion_meses" class="form-label">Duración (meses)</label>
          			<input type="number" name="duracion_meses" id="duracion_meses" class="form-control"
                 		value="<?= (int)$registro['duracion_meses'] ?>" min="0" <?= $modo_ver ? 'readonly' : '' ?>>
        		</div>

        		<!-- Flags funcionales -->
        		<div class="row mb-3">
          			<div class="col-md-4">
            			<div class="form-check">
              				<input class="form-check-input" type="checkbox" name="requiere_inicio" id="requiere_inicio" value="1"
                     		<?= $registro['requiere_inicio'] ? 'checked' : '' ?> <?= $modo_ver ? 'disabled' : '' ?>>
              				<label class="form-check-label" for="requiere_inicio">Requiere fecha de inicio</label>
            			</div>
          			</div>
          			<div class="col-md-4">
            			<div class="form-check">
              				<input class="form-check-input" type="checkbox" name="requiere_vencimiento" id="requiere_vencimiento" value="1"
                     		<?= $registro['requiere_vencimiento'] ? 'checked' : '' ?> <?= $modo_ver ? 'disabled' : '' ?>>
              				<label class="form-check-label" for="requiere_vencimiento">Requiere vencimiento</label>
            			</div>
          			</div>
          			<div class="col-md-4">
            			<div class="form-check">
              				<input class="form-check-input" type="checkbox" name="requiere_archivo" id="requiere_archivo" value="1"
                     		<?= $registro['requiere_archivo'] ? 'checked' : '' ?> <?= $modo_ver ? 'disabled' : '' ?>>
              				<label class="form-check-label" for="requiere_archivo">Requiere archivo adjunto</label>
            			</div>
          			</div>
        		</div>

        		<div class="row mb-3">
  					<!-- Código interno -->
  					<div class="col-md-4">
    					<label for="codigo_interno" class="form-label">Código interno</label>
    					<input type="text" name="codigo_interno" id="codigo_interno" class="form-control"
           					value="<?= htmlspecialchars($registro['codigo_interno']) ?>" maxlength="20" <?= $modo_ver ? 'readonly' : '' ?>>
  					</div>
  					<!-- Grupo funcional -->
  					<div class="col-md-4">
    					<label for="grupo" class="form-label">Grupo funcional</label>
    					<input type="text" name="grupo" id="grupo" class="form-control"
           					value="<?= htmlspecialchars($registro['grupo']) ?>" maxlength="50" <?= $modo_ver ? 'readonly' : '' ?>>
  					</div>
  					<!-- ícono -->
  					<div class="col-md-4">
            			<label for="icono" class="form-label">Ícono (FontAwesome)</label>
            			<input type="text" name="icono" id="icono" class="form-control"
                   			value="<?= htmlspecialchars($registro['icono']) ?>" placeholder="ej. fas fa-file-alt" <?= $modo_ver ? 'readonly' : '' ?>>
          			</div>
        		</div>
			</div>

    		<div class="modal-footer">
        		<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
          			<i class="fas fa-times me-1"></i> <?= $modo_ver ? 'Cerrar' : 'Cancelar' ?>
        		</button>

        		<?php if ($modo_ver): ?>
          			<button type="button" class="btn btn-outline-secondary" id="btn-imprimir">
            			<i class="fas fa-print me-1"></i> Imprimir
          			</button>
        		<?php else: ?>
          			<button type="submit" class="btn btn-primary" id="btn-guardar">
            			<i class="fas fa-save me-1"></i> Guardar
          			</button>
        		<?php endif; ?>
      		</div>
    	</form>
  	</div>
</div>

<?php if ($modo_ver): ?>
	<script>
  		document.addEventListener('DOMContentLoaded', () => {
    		const btnImprimir = document.getElementById('btn-imprimir');
    		if (btnImprimir) {
      			btnImprimir.addEventListener('click', () => {
        			window.print();
      				});
    			}
  			});
	</script>
<?php endif; ?>