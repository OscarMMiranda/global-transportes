<?php
    // archivo: /modulos/empleados/modales/modal_historial.php
?>

<div class="modal fade" id="modalHistorial" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <!-- HEADER CORPORATIVO -->
            <div class="modal-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h5 class="modal-title">
                    <i class="fa fa-history"></i> Historial del Empleado
                </h5>

                <!-- BOTÓN CERRAR SIEMPRE VISIBLE -->
                <!-- <button type="button" class="btn btn-dark btn-sm" data-bs-dismiss="modal">
                    Cerrar
                </button> -->
            </div>

            <!-- CONTENIDO DEL HISTORIAL -->
            <div class="modal-body" id="historialContenido">
                <div class="text-center text-muted py-4">
                    Cargando historial...
                </div>
            </div>

            <!-- FOOTER (OPCIONAL) -->
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>
