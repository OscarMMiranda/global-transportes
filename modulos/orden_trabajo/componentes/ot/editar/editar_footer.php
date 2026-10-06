<?php
// ======================================================
// COMPONENTE: editar_footer.php
// MÓDULO: Órdenes de Trabajo
// RESPONSABILIDAD: Footer corporativo del modal editar
// GLOBAL 2026
// ======================================================
?>

<div class="modal-footer bg-white border-top py-3">

    <div class="w-100">

        <!-- MENSAJES -->
        <div id="msgEditarOT" class="mb-2"></div>

        <div class="d-flex justify-content-between align-items-center">

            <!-- INFORMACIÓN -->
            <div class="text-muted small">

                <i class="fa-solid fa-circle-info me-1"></i>

                Los cambios realizados quedarán registrados en el historial de la Orden de Trabajo.

            </div>

            <!-- BOTONES -->
            <div class="d-flex align-items-center gap-2">

                <!-- LOADER -->
                <div
                    id="loaderEditarOT"
                    class="d-none me-2">

                    <span
                        class="spinner-border spinner-border-sm text-primary"
                        role="status"
                        aria-hidden="true">
                    </span>

                    <span class="small text-muted ms-1">

                        Guardando...

                    </span>

                </div>

                <!-- CANCELAR -->
                <button
                    type="button"
                    class="btn btn-outline-secondary px-4"
                    data-bs-dismiss="modal">

                    <i class="fa-solid fa-xmark me-2"></i>

                    Cancelar

                </button>

                <!-- GUARDAR -->
                <button
                    type="button"
                    id="ot-btn-guardar-editar"
                    class="btn btn-primary px-4 fw-bold">

                    <i class="fa-solid fa-floppy-disk me-2"></i>

                    Guardar Cambios

                </button>

            </div>

        </div>

    </div>

</div>