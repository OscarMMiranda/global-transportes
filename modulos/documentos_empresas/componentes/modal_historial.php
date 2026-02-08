<!-- /modulos/documentos_empresas/componentes/modal_historial.php -->

<div class="modal fade" id="modalHistorial" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Historial de documentos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- CLAVE: modal-body hace scroll automáticamente -->
            <div class="modal-body">

                <table class="table table-bordered table-sm" id="tablaHistorial">
                    <thead class="table-light">
                        <tr>
                            <th>Versión</th>
                            <th>Número</th>
                            <th>Inicio</th>
                            <th>Vencimiento</th>
                            <th>Archivo</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>
