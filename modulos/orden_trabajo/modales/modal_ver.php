<!-- ARCHIVO: modulos/orden_trabajo/modales/modal_ver.php -->

<div class="modal fade" id="modalVerOT" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content shadow-lg">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold">Detalle de Orden de Trabajo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body px-4">

                <!-- ============================
                     SECCIÓN: DATOS DE LA OT
                ============================ -->
                <h6 class="fw-bold text-secondary border-bottom pb-2 mb-3">
                    Información General
                </h6>

                <div id="bloqueDatosOT" class="mb-4">
                    <!-- Aquí se cargará ver.php -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center text-muted py-4">
                            Cargando información...
                        </div>
                    </div>
                </div>

                <!-- ============================
                     SECCIÓN: VIAJES ASOCIADOS
                ============================ -->
                <h6 class="fw-bold text-secondary border-bottom pb-2 mb-3">
                    Viajes Registrados
                </h6>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle" id="tablaViajesOT">

                        <thead class="table-dark">
                            <tr>
                                <th style="width: 90px;">N° Viaje</th>
                                <th>Fecha</th>
                                <th>Semana</th>
                                <th>Vehículo</th>
                                <th>Conductor</th>
                                <th>Origen</th>
                                <th>Destino</th>
                                <th>Mercadería</th>
                                <th>Estado</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-3">
                                    Sin viajes registrados
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer py-3">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>
