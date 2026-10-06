<?php
// ======================================================
// COMPONENTE: modal_registrar_viaje_component.php
// MÓDULO: Órdenes de Trabajo
// RESPONSABILIDAD: Registrar nueva OV
// GLOBAL 2026
// ======================================================
?>

<div class="modal fade"
     id="modalRegistrarViaje"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-scrollable">

        <div class="modal-content">

            <!-- ===================================== -->
            <!-- HEADER -->
            <!-- ===================================== -->

            <div class="modal-header bg-primary text-white">

                <div>

                    <h5 class="modal-title mb-0">

                        <i class="fa-solid fa-road me-2"></i>

                        Registrar Viaje

                    </h5>

                    <small class="opacity-75">

                        Nueva Orden de Vehículo (OV)

                    </small>

                </div>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <!-- ===================================== -->
            <!-- BODY -->
            <!-- ===================================== -->

            <div class="modal-body">

                <input type="hidden"
                       id="rv_ot_id">

                <input type="hidden"
                       id="rv_conductor_id">

                <div class="accordion"
                     id="accordionRegistrarViaje">

                    <?php include __DIR__ . '/viaje_ot_info_component.php'; ?>

                    <?php include __DIR__ . '/viaje_operacion_component.php'; ?>

                    <?php include __DIR__ . '/viaje_ruta_component.php'; ?>

                    <?php include __DIR__ . '/viaje_carga_component.php'; ?>

                    <?php include __DIR__ . '/viaje_observaciones_component.php'; ?>

                </div>

            </div>

            <!-- ===================================== -->
            <!-- FOOTER -->
            <!-- ===================================== -->

            <div class="modal-footer">

                <div class="me-auto text-muted small">

                    Complete la información mínima para crear la OV.

                </div>

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                    <i class="fa-solid fa-xmark me-1"></i>

                    Cancelar

                </button>

                <button type="button"
                        class="btn btn-success"
                        id="btnGuardarViaje">

                    <i class="fa-solid fa-floppy-disk me-1"></i>

                    Registrar Viaje

                </button>

            </div>

        </div>

    </div>

</div>