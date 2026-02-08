<?php
    // archivo: /modulos/asistencias/modales/modal_registrar_asistencia.php
?>

<!-- Modal: Registrar Asistencia -->
<div class="modal fade" id="modalRegistrarAsistencia" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Registrar Asistencia</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- CONTENEDOR PARA ALERTAS VISUALES -->
                <div id="alertaAsistencia"></div>

                <?php 
                    include '../componentes/form_registrar_asistencia.php'; 
                ?>

            </div>

            <div class="modal-footer">
                <button type="button" id="btnRegistrarAsistencia" class="btn btn-primary">
                    Guardar asistencia
                </button>

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>
