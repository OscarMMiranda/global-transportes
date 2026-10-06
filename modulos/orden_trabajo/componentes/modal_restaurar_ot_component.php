<?php
// ======================================================
//  COMPONENTE: modal_restaurar_ot_component.php
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Modal corporativo para restaurar OT eliminada
//  GLOBAL 2026 — Arquitectura Limpia
// ======================================================
?>

<div class="modal fade"
     id="ot-modal-restaurar"
     tabindex="-1"
     aria-hidden="true"
     data-modulo="orden_trabajo"
     data-componente="modal_restaurar_ot"
     data-version="3.0"
     data-auditoria="modal_restaurar_ot">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold">
                    Restaurar Orden de Trabajo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <!-- ID oculto -->
                <input type="hidden" id="restaurar_id_ot">

                <p class="mb-3 text-center">
                    ¿Desea <strong>restaurar</strong> esta Orden de Trabajo?
                </p>

                <div class="alert alert-success text-center">
                    La OT volverá a estar disponible en el listado principal.
                </div>

                <!-- Loader corporativo -->
                <div id="loaderRestaurarOT" class="text-center my-3" style="display:none;">
                    <div class="spinner-border text-success"></div>
                    <p class="mt-2">Procesando restauración...</p>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary btn-sm"
                        data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button type="button"
                        class="btn btn-success btn-sm"
                        id="ot-btn-confirmar-restaurar">
                    Restaurar OT
                </button>

            </div>

        </div>
    </div>

</div>
