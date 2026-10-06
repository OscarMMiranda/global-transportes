<?php
// ======================================================
//  COMPONENTE: modal_anular_ot_component.php
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Modal corporativo para anular OT
//  GLOBAL 2026 — Arquitectura Limpia (Optimizado Visual)
// ======================================================
?>

<div class="modal fade"
     id="modalAnularOT"
     tabindex="-1"
     aria-hidden="true"
     data-componente="modal_anular_ot"
     data-version="4.1">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-corp">

            <!-- HEADER corporativo -->
            <div class="modal-header bg-corp-warning text-dark py-2">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-ban fa-fw me-1"></i>
                    Anular Orden de Trabajo
                </h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <!-- ID oculto -->
                <input type="hidden" id="anular_id_ot">

                <p class="mb-3">
                    ¿Está seguro que desea <strong>anular</strong> esta Orden de Trabajo?
                </p>

                <div class="alert alert-warning">
                    La OT no será eliminada, pero quedará marcada como <strong>ANULADA</strong>.
                </div>

                <!-- Loader corporativo -->
                <div id="loaderAnularOT" class="overlay-corp d-none mt-3">
                    <div class="overlay-corp-content">
                        <div class="spinner-border text-warning"></div>
                        <span class="text-dark fw-bold">Procesando anulación...</span>
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
                        class="btn btn-corp-warning btn-sm px-3 fw-bold"
                        id="ot-btn-confirmar-anular">
                    <i class="fa-solid fa-ban fa-fw me-1"></i>
                    Anular OT
                </button>

            </div>

        </div>
    </div>

</div>
