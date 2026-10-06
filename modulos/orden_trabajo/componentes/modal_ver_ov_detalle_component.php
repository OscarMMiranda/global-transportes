<!-- ======================================================
     MODAL: VER DETALLE OV
     MÓDULO: Órdenes de Vehículo
     RESPONSABILIDAD: Ficha Operativa Completa
     GLOBAL 2026
====================================================== -->

<div class="modal fade"
     id="modalVerOVDetalle"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-fullscreen-xl-down modal-xl modal-dialog-scrollable">

        <div class="modal-content ov-detalle-card">

            <!-- HEADER -->

            <div class="modal-header ov-header">

                <div>

                    <h4 class="modal-title mb-0 fw-bold">

                        <i class="fa-solid fa-truck-fast me-2"></i>

                        OV <span id="ovd_numero_ov">0001-2026</span>

                    </h4>

                    <small>

                        Orden de Trabajo
                        <strong id="ovd_numero_ot">
                            0025-2026
                        </strong>

                    </small>

                </div>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body bg-light">

                <!-- RESUMEN -->

                <div class="row g-3 mb-3">

                    <div class="col-md-3">

                        <div class="ov-kpi">

                            <div class="ov-kpi-label">
                                Cliente
                            </div>

                            <div id="ovd_cliente"
                                 class="ov-kpi-value">

                                -
                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="ov-kpi">

                            <div class="ov-kpi-label">
                                Tipo OT
                            </div>

                            <div id="ovd_tipo_ot"
                                 class="ov-kpi-value">

                                -
                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="ov-kpi">

                            <div class="ov-kpi-label">
                                Fecha
                            </div>

                            <div id="ovd_fecha"
                                 class="ov-kpi-value">

                                -
                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="ov-kpi">

                            <div class="ov-kpi-label">
                                Estado
                            </div>

                            <div id="ovd_estado">

                                <span class="badge bg-success">
                                    ACTIVO
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- ASIGNACION -->

                <div class="card shadow-sm mb-3">

                    <div class="card-header fw-bold">

                        <i class="fa-solid fa-truck me-2"></i>

                        Asignación

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4">
                                <label class="text-muted small">
                                    Vehículo
                                </label>

                                <div id="ovd_vehiculo"></div>
                            </div>

                            <div class="col-md-4">
                                <label class="text-muted small">
                                    Conductor
                                </label>

                                <div id="ovd_conductor"></div>
                            </div>

                            <div class="col-md-4">
                                <label class="text-muted small">
                                    Remolque
                                </label>

                                <div id="ovd_remolque"></div>
                            </div>

                        </div>

                    </div>

                </div>

                <!-- RUTA -->

                <div class="card shadow-sm mb-3">

                    <div class="card-header fw-bold">

                        <i class="fa-solid fa-route me-2"></i>

                        Ruta Operativa

                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-3">

                                <label class="text-muted small">
                                    Origen
                                </label>

                                <div id="ovd_origen"></div>

                            </div>

                            <div class="col-md-3">

                                <label class="text-muted small">
                                    Destino
                                </label>

                                <div id="ovd_destino"></div>

                            </div>

                            <div class="col-md-3">

                                <label class="text-muted small">
                                    Zona
                                </label>

                                <div id="ovd_zona"></div>

                            </div>

                            <div class="col-md-3">

                                <label class="text-muted small">
                                    Tipo Servicio
                                </label>

                                <div id="ovd_tipo_servicio"></div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- CARGA -->

                <div class="card shadow-sm mb-3">

                    <div class="card-header fw-bold">

                        <i class="fa-solid fa-box me-2"></i>

                        Información de Carga

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-3">
                                <label class="small text-muted">
                                    Contenedor
                                </label>
                                <div id="ovd_contenedor"></div>
                            </div>

                            <div class="col-md-3">
                                <label class="small text-muted">
                                    Tipo
                                </label>
                                <div id="ovd_tipo_contenedor"></div>
                            </div>

                            <div class="col-md-3">
                                <label class="small text-muted">
                                    Peso
                                </label>
                                <div id="ovd_peso"></div>
                            </div>

                            <div class="col-md-3">
                                <label class="small text-muted">
                                    Payload
                                </label>
                                <div id="ovd_payload"></div>
                            </div>

                        </div>

                    </div>

                </div>

                <!-- DOCUMENTOS -->

                <div class="card shadow-sm mb-3">

                    <div class="card-header fw-bold">

                        <i class="fa-solid fa-file-lines me-2"></i>

                        Documentación

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-4">

                                <label class="small text-muted">

                                    DAM / Booking

                                </label>

                                <div id="ovd_documento_principal"></div>

                            </div>

                            <div class="col-md-4">

                                <label class="small text-muted">

                                    EIR

                                </label>

                                <div id="ovd_eir"></div>

                            </div>

                            <div class="col-md-4">

                                <label class="small text-muted">

                                    OC Cliente

                                </label>

                                <div id="ovd_oc_cliente"></div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- COSTOS -->

                <div class="row g-3">

                    <div class="col-md-4">

                        <div class="ov-costo-card">

                            <span>Combustible</span>

                            <h5 id="ovd_combustible">
                                S/ 0.00
                            </h5>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="ov-costo-card">

                            <span>Peaje</span>

                            <h5 id="ovd_peaje">
                                S/ 0.00
                            </h5>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="ov-costo-card">

                            <span>Viático</span>

                            <h5 id="ovd_viatico">
                                S/ 0.00
                            </h5>

                        </div>

                    </div>

                </div>

            </div>

            <div class="modal-footer bg-light">

                <button type="button"
                        class="btn btn-warning">

                    <i class="fa-solid fa-pen me-1"></i>

                    Editar

                </button>

                <button type="button"
                        class="btn btn-info text