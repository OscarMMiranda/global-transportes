<?php
// ======================================================
// COMPONENTE: modal_ver_ov_component.php
// MÓDULO: Órdenes de Vehículo (OV)
// RESPONSABILIDAD: Gestión de Viajes de una OT
// GLOBAL 2026
// ======================================================
?>

<div class="modal fade"
     id="modalVerOV"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-fullscreen-xl-down modal-xl modal-dialog-scrollable">

        <div class="modal-content shadow border-0">

            <!-- HEADER -->

            <div class="modal-header bg-primary text-white">

                <div>

                    <h5 class="modal-title fw-bold mb-0">

                        <i class="fa-solid fa-route me-2"></i>

                        Gestión de Viajes

                    </h5>

                    <small id="ovTituloOT">

                        OT: -

                    </small>

                </div>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <!-- BODY -->

            <div class="modal-body p-3">

                <!-- RESUMEN OT -->

                <div class="row g-3 mb-3">

                    <div class="col-md-3">

                        <div class="card border-0 bg-light h-100">

                            <div class="card-body">

                                <small class="text-muted">
                                    Cliente
                                </small>

                                <div id="ovCliente"
                                     class="fw-bold">
                                    -
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="card border-0 bg-light h-100">

                            <div class="card-body">

                                <small class="text-muted">
                                    Tipo OT
                                </small>

                                <div id="ovTipoOT"
                                     class="fw-bold">
                                    -
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="card border-0 bg-light h-100">

                            <div class="card-body">

                                <small class="text-muted">
                                    Empresa
                                </small>

                                <div id="ovEmpresa"
                                     class="fw-bold">
                                    -
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="card border-0 bg-light h-100">

                            <div class="card-body">

                                <small class="text-muted">
                                    Viajes
                                </small>

                                <div id="badgeCantidadOV"
                                     class="fw-bold text-primary">
                                    0
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- MENSAJE SIN VIAJES -->

                <div id="contenedorSinOV"
                     class="d-none">

                    <div class="text-center py-5">

                        <div class="mb-3">

                            <i class="fa-solid fa-truck-ramp-box
                                      text-warning"
                               style="font-size:60px;">
                            </i>

                        </div>

                        <h4 class="fw-bold">

                            No existen viajes registrados

                        </h4>

                        <p class="text-muted">

                            Esta Orden de Trabajo aún no tiene
                            Órdenes de Vehículo asociadas.

                        </p>

                        <button type="button"
                                class="btn btn-primary"
                                id="btnRegistrarPrimerViaje">

                            <i class="fa-solid fa-plus me-1"></i>

                            Registrar Primer Viaje

                        </button>

                    </div>

                </div>

                <!-- TABLA VIAJES -->

                <div id="contenedorOV">

                    <div class="card border-0 shadow-sm">

                        <div class="card-header">

                            <strong>

                                Viajes Registrados

                            </strong>

                        </div>

                        <div class="card-body p-0">

                            <div class="table-responsive">

                                <table class="table table-hover table-striped align-middle mb-0"
                                       id="tablaOVViajes">

                                    <thead class="table-light">

                                        <tr>

                                            <th width="80">
                                                # Viaje
                                            </th>

                                            <th width="120">
                                                Fecha
                                            </th>

                                            <th width="110">
                                                # OV
                                            </th>

                                            <th width="120">
                                                Vehículo
                                            </th>

                                            <th width="220">
                                                Conductor
                                            </th>

                                            <th>
                                                Origen
                                            </th>

                                            <th>
                                                Destino
                                            </th>

                                            <th width="220">
                                                Carga
                                            </th>

                                            <th width="110"
                                                class="text-center">

                                                Acciones

                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- FOOTER -->

            <div class="modal-footer bg-light">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                    Cerrar

                </button>

            </div>

        </div>

    </div>

</div>