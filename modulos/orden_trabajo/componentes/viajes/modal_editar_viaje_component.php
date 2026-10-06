<?php
// ======================================================
//  COMPONENTE: modal_editar_viaje_component.php
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Modal corporativo para editar viaje
//  GLOBAL 2026 — Arquitectura Limpia (Optimizado Visual)
// ======================================================
?>

<div class="modal fade"
     id="modalEditarViaje"
     tabindex="-1"
     aria-hidden="true"
     data-componente="modal_editar_viaje"
     data-version="4.1">

    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content card-corp">

            <!-- HEADER corporativo -->
            <div class="modal-header bg-corp-primary text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-truck fa-fw me-1"></i>
                    Editar Viaje
                </h5>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <!-- Overlay interno corporativo -->
                <div id="overlay-editar-viaje" class="overlay-corp d-none">
                    <div class="overlay-corp-content">
                        <div class="spinner-border text-light"></div>
                        <span>Cargando viaje...</span>
                    </div>
                </div>

                <form id="formEditarViaje">

                    <!-- IDs ocultos -->
                    <input type="hidden" id="editar_viaje_id">
                    <input type="hidden" id="editar_viaje_ot_id">

                    <!-- Fecha -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Fecha del viaje:</label>
                        <input type="date"
                               class="form-control form-control-sm"
                               id="editar_viaje_fecha"
                               name="fecha"
                               required>
                    </div>

                    <!-- Origen / Destino -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Origen:</label>
                            <input type="text"
                                   class="form-control form-control-sm"
                                   id="editar_viaje_origen"
                                   name="origen"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Destino:</label>
                            <input type="text"
                                   class="form-control form-control-sm"
                                   id="editar_viaje_destino"
                                   name="destino"
                                   required>
                        </div>
                    </div>

                    <!-- Conductor -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Conductor:</label>
                        <input type="text"
                               class="form-control form-control-sm"
                               id="editar_viaje_conductor"
                               name="conductor"
                               required>
                    </div>

                    <!-- Placa -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Placa:</label>
                        <input type="text"
                               class="form-control form-control-sm"
                               id="editar_viaje_placa"
                               name="placa"
                               required>
                    </div>

                    <!-- Observaciones -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Observaciones:</label>
                        <textarea class="form-control form-control-sm"
                                  id="editar_viaje_observaciones"
                                  name="observaciones"
                                  rows="3"></textarea>
                    </div>

                </form>

            </div>

            <!-- FOOTER corporativo -->
            <div class="modal-footer bg-light">

                <button type="button"
                        class="btn btn-corp-secondary btn-sm px-3"
                        data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark fa-fw me-1"></i>
                    Cancelar
                </button>

                <button type="button"
                        class="btn btn-corp-primary btn-sm px-3 fw-bold"
                        onclick="guardarEdicionViaje()">
                    <i class="fa-solid fa-floppy-disk fa-fw me-1"></i>
                    Guardar cambios
                </button>

            </div>

        </div>
    </div>
</div>
