<?php
// archivo: /modulos/vehiculos/modales/modal_editar.php
?>
<div class="modal fade" id="modalEditarVehiculo2" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable">
    	<div class="modal-content">

			<div class="modal-header bg-primary text-white">
				<h5 class="modal-title">
					<i class="fas fa-edit me-2"></i>Editar Vehículo
				</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
			</div>

            <div class="modal-body">
                <ul class="nav nav-tabs" id="tabsEditarVehiculo">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-datos">
                            Datos Técnicos
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-ficha-editar">
                            Ficha Técnica
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-config">
                            Configuración Operativa
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-docs">
                            Documentos
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-auditoria">
                            Auditoría
                        </button>
                    </li>
                </ul>
				
                <div class="tab-content mt-3">

                    <div class="tab-pane fade show active" id="tab-datos"></div>

                    <div class="tab-pane fade" id="tab-ficha-editar"></div>

                    <div class="tab-pane fade" id="tab-config"></div>

                    <div class="tab-pane fade" id="tab-docs"></div>

                    <div class="tab-pane fade" id="tab-auditoria"></div>

                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>

