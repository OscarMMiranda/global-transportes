<!-- ARCHIVO: modulos/orden_trabajo/modales/modal_ver.php -->

<div class="modal fade" id="modalVerOT" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Detalle de Orden de Trabajo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <!-- ============================
                     SECCIÓN: DATOS DE LA OT
                ============================ -->
                <div id="bloqueDatosOT" class="mb-4">
                    <!-- Aquí se cargará ver.php -->
                    <div class="p-3 text-center text-muted">
                        Cargando información...
                    </div>
                </div>

                <hr>

                <!-- ============================
                     SECCIÓN: VIAJES ASOCIADOS
                ============================ -->
                <h5 class="mb-3">Viajes Registrados</h5>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tablaViajesOT">
                        <thead class="table-dark">
                            <tr>
                                <th>N° Viaje</th>
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
                                <td colspan="9" class="text-center text-muted">Sin viajes registrados</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>
