<?php
// ======================================================
// COMPONENTE: modal_ver_ot_component.php
// MÓDULO: Órdenes de Trabajo (OT)
// RESPONSABILIDAD: Modal corporativo para ver OT
// GLOBAL 2026
// ======================================================
?>

<div class="modal fade"
     id="modalVerOT"
     tabindex="-1"
     aria-hidden="true"
     data-componente="modal_ver_ot">

    <div class="modal-dialog modal-lg modal-dialog-scrollable">

        <div class="modal-content shadow-sm border-0">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white py-2">

                <h5 class="modal-title fw-bold mb-0">
                    <i class="fa-solid fa-eye me-2"></i>
                    Ver Orden de Trabajo
                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>

            </div>

            <!-- BODY -->
            <div class="modal-body py-2 position-relative">

                <!-- OVERLAY -->
                <div id="overlay-ver-ot"
                     class="overlay-corp d-none">

                    <div class="overlay-corp-content">

                        <div class="spinner-border text-light"></div>

                        <span>
                            Cargando información...
                        </span>

                    </div>

                </div>

                <!-- RESUMEN OT -->
                <div class="card mb-2">

                    <div class="card-header py-2 fw-bold">

                        <i class="fa-solid fa-file-lines me-1"></i>
                        Información General

                    </div>

                    <div class="card-body py-2">

                        <div id="contenedorVerOT">

                            <!-- Se carga dinámicamente -->

                        </div>

                    </div>

                </div>

                <!-- VIAJES -->
                <div class="card">

                    <div class="card-header py-2">

                        <div class="d-flex justify-content-between align-items-center">

                            <div class="fw-bold">

                                <i class="fa-solid fa-route me-1"></i>
                                Viajes Registrados

                            </div>

                            <span
                                id="badgeCantidadViajes"
                                class="badge bg-primary">

                                0 Viajes

                            </span>

                        </div>

                    </div>

                    <div class="card-body py-2">

                        <div class="table-responsive">

                            <table
                                class="table table-sm table-hover align-middle mb-0"
                                id="tablaViajesOT">

                                <thead class="table-light">

                                    <tr>
                                        <th>N° Viaje</th>
                                        <th>Fecha</th>
                                        <th>N° OV</th>
                                        <th>Vehículo</th>
                                        <th>Conductor</th>
                                        <th>Origen</th>
                                        <th>Destino</th>
                                        <th>Mercadería</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    <!-- Dinámico -->

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

            <!-- FOOTER -->

            <div class="modal-footer py-2 bg-light">

                <button type="button"
                        class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">

                    <i class="fa-solid fa-xmark me-1"></i>
                    Cerrar

                </button>

            </div>

        </div>

    </div>

</div>