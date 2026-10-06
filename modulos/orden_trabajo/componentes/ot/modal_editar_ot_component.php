<?php
// ======================================================
// COMPONENTE: modal_editar_ot_component.php
// MÓDULO: Órdenes de Trabajo
// RESPONSABILIDAD: Contenedor principal del modal
// GLOBAL 2026
// ======================================================
?>

<div class="modal fade"
     id="modalEditarOT"
     tabindex="-1"
     aria-hidden="true"
     data-componente="modal_editar_ot">

    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content card-corp shadow-sm border-0">

            <!-- ========================================= -->
            <!-- HEADER -->
            <!-- ========================================= -->
            <div class="modal-header bg-corp-primary text-white">
            <div class="modal-header ot-modal-info-header">

                <div>

                    <h5 class="modal-title fw-bold mb-0">

                        <i class="fa-solid fa-pen-to-square me-2"></i>

                        Editar Orden de Trabajo

                    </h5>

                    <small class="opacity-75">

                        Modifique la información de la OT

                    </small>

                </div>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <!-- ========================================= -->
            <!-- BODY -->
            <!-- ========================================= -->
            <div class="modal-body bg-light">

                <!-- RESUMEN -->
                <?php include __DIR__ . '/editar/editar_resumen.php'; ?>

                <form id="ot-form-editar">

                    <input
                        type="hidden"
                        name="id"
                        id="editar_id">

                    <!-- INFORMACIÓN GENERAL -->
                    <?php include __DIR__ . '/editar/editar_general.php'; ?>

                    <!-- CLIENTE Y EMPRESA -->
                    <?php include __DIR__ . '/editar/editar_cliente_empresa.php'; ?>

                    <!-- INFORMACIÓN OPERATIVA -->
                    <?php include __DIR__ . '/editar/editar_operacion.php'; ?>

                </form>

            </div>

            <!-- ========================================= -->
            <!-- FOOTER -->
            <!-- ========================================= -->
            <?php include __DIR__ . '/editar/editar_footer.php'; ?>

        </div>

    </div>

</div>