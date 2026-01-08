<?php
	// archivo: /modulos/usuarios/modales/modal_desactivar.php
	// --------------------------------------------------------------
	// Modal para confirmar desactivación de usuario
	// --------------------------------------------------------------   
?>

<!-- Modal de confirmación de desactivación -->
<div class="modal fade" id="modalDesactivar" tabindex="-1" aria-labelledby="modalDesactivarLabel" aria-hidden="true" data-bs-backdrop="static">
	<div class="modal-dialog modal-dialog-centered">
    	<div class="modal-content border-0 shadow">
      
    		<div class="modal-header bg-warning text-dark">
				<h5 class="modal-title" id="modalDesactivarLabel">
					<i class="fa fa-user-slash me-2"></i> Confirmar desactivación
        		</h5>
				<button 
					type="button" 
					class="btn-close" 
					data-bs-dismiss="modal">
				</button>
			</div>

      		<div class="modal-body">
        		<p class="mb-1">
					¿Estás seguro de que deseas <strong>desactivar</strong> este usuario?
				</p>
        		<p class="text-muted small">
					Esta acción impide el acceso al sistema, pero no elimina la cuenta.
				</p>
      		</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
				<a id="btnConfirmarDesactivacion" href="#" class="btn btn-warning">Aceptar</a>
			</div>
		</div>
	</div>
</div>

<script>
function abrirModalDesactivar(idUsuario) {
  const modal = new bootstrap.Modal(document.getElementById('modalDesactivar'));
  document.getElementById('btnConfirmarDesactivacion').href =
    "/modulos/usuarios/acciones/desactivar.php?id=" + idUsuario;
  modal.show();
}
</script>