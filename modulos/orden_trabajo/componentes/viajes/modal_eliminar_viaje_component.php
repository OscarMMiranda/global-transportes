<?php
// ======================================================
//  COMPONENTE: modal_eliminar_viaje_component.php
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Eliminación lógica de viaje asociado a OT
//  GLOBAL 2026 — Arquitectura Limpia (Optimizado Visual)
// ======================================================
?>

<div class="modal fade"
     id="modalEliminarViaje"
     tabindex="-1"
     aria-hidden="true"
     data-componente="modal_eliminar_viaje"
     data-version="4.1">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-corp">

            <!-- HEADER corporativo -->
            <div class="modal-header bg-corp-danger text-white py-2">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-triangle-exclamation fa-fw me-1"></i>
                    Eliminar Viaje
                </h5>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <!-- ID oculto -->
                <input type="hidden" id="eliminar_viaje_id">
                <input type="hidden" id="eliminar_viaje_ot_id">

                <p class="mb-3 text-center">
                    ¿Está seguro que desea <strong>eliminar</strong> este viaje?
                    <br>
                    <strong class="text-danger">Esta acción no elimina el viaje físicamente.</strong>
                </p>

                <div class="alert alert-danger text-center">
                    El viaje será marcado como <strong>ELIMINADO</strong> y no aparecerá en el listado.
                </div>

                <!-- Loader corporativo -->
                <div id="loaderEliminarViaje" class="overlay-corp d-none mt-3">
                    <div class="overlay-corp-content">
                        <div class="spinner-border text-danger"></div>
                        <span class="fw-bold text-dark">Procesando eliminación...</span>
                    </div>
                </div>

            </div>

            <!-- FOOTER corporativo -->
            <div class="modal-footer bg-light py-2">

                <button type="button"
                        class="btn btn-corp-secondary btn-sm px-3"
                        data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark fa-fw me-1"></i>
                    Cancelar
                </button>

                <button type="button"
                        class="btn btn-corp-danger btn-sm px-3 fw-bold"
                        id="btnConfirmarEliminarViaje">
                    <i class="fa-solid fa-trash fa-fw me-1"></i>
                    Eliminar Viaje
                </button>

            </div>

        </div>
    </div>
</div>
