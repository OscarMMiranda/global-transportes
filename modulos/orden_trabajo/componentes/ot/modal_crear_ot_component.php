<?php
// ======================================================
// COMPONENTE: modal_crear_ot_component.php
// MÓDULO: Órdenes de Trabajo
// RESPONSABILIDAD: Modal creación OT
// GLOBAL 2026
// ======================================================
?>

<div class="modal fade"
     id="modal-nueva-ot"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content shadow-sm border-0">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white py-2">

                <h5 class="modal-title fw-bold mb-0">
                    <i class="fa-solid fa-file-circle-plus me-2"></i>
                    Nueva Orden de Trabajo
                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>

            </div>

            <!-- BODY -->
            <div class="modal-body py-2">

                <form id="ot-form-crear">

                    <!-- DATOS DE CONTROL -->
                    <div class="card mb-2">

                        <div class="card-header py-2 fw-bold">
                            Datos de Control
                        </div>

                        <div class="card-body py-2">

                            <div class="row g-2">

                                <div class="col-md-6">

                                    <label class="form-label fw-bold small mb-1">
                                        Número OT
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        name="numero_ot"
                                        id="crear_numero_ot">

                                    <small class="text-muted">
                                        Puede modificarse para OTs históricas.
                                    </small>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label fw-bold small mb-1">
                                        Fecha
                                    </label>

                                    <input
                                        type="date"
                                        class="form-control form-control-sm"
                                        name="fecha"
                                        id="crear_fecha">

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- INFORMACIÓN COMERCIAL -->
                    <div class="card mb-2">

                        <div class="card-header py-2 fw-bold">
                            Información Comercial
                        </div>

                        <div class="card-body py-2">

                            <div class="row g-2">

                                <div class="col-md-6">

                                    <label class="form-label fw-bold small mb-1">
                                        Empresa
                                    </label>

                                    <select
                                        class="form-select form-select-sm"
                                        name="empresa_id"
                                        id="crear_empresa_id">

                                        <option value="">
                                            Seleccione...
                                        </option>

                                    </select>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label fw-bold small mb-1">
                                        Cliente
                                    </label>

                                    <select
                                        class="form-select form-select-sm"
                                        name="cliente_id"
                                        id="crear_cliente_id">

                                        <option value="">
                                            Seleccione...
                                        </option>

                                    </select>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- INFORMACIÓN OPERATIVA -->
                    <div class="card mb-2">

                        <div class="card-header py-2 fw-bold">
                            Información Operativa
                        </div>

                        <div class="card-body py-2">

                            <div class="row g-2">

                                <div class="col-md-6">

                                    <label class="form-label fw-bold small mb-1">
                                        Tipo OT
                                    </label>

                                    <select
                                        class="form-select form-select-sm"
                                        name="tipo_ot_id"
                                        id="crear_tipo_ot_id">

                                        <option value="">
                                            Seleccione...
                                        </option>

                                    </select>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label fw-bold small mb-1">
                                        OC Cliente
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        name="oc_cliente">

                                </div>

                            </div>

                            <div class="row mt-2">

                                <div class="col-md-12 d-none"
                                     id="crear_campo_dinamico">

                                    <div class="alert alert-info py-2 mb-0">

                                        <label
                                            class="form-label fw-bold small mb-1"
                                            id="crear_campo_dinamico_label">
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control form-control-sm"
                                            name="campo_dinamico"
                                            id="crear_campo_dinamico_input">

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer py-2">

                <button type="button"
                        class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">

                    <i class="fa-solid fa-xmark me-1"></i>
                    Cancelar

                </button>

                <button type="button"
                        class="btn btn-primary btn-sm fw-bold"
                        id="ot-btn-guardar-crear">

                    <i class="fa-solid fa-floppy-disk me-1"></i>
                    Guardar OT

                </button>

            </div>

        </div>

    </div>

</div>