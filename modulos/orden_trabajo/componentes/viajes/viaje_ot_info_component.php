<?php
// ======================================================
// COMPONENTE: viaje_ot_info_component.php
// MÓDULO: Viajes
// RESPONSABILIDAD: Información de la OT asociada
// GLOBAL 2026
// ======================================================
?>

<div class="accordion-item">

    <h2 class="accordion-header">

        <button class="accordion-button"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#accOT"
                aria-expanded="true">

            <i class="fa-solid fa-file-contract me-2"></i>

            Información de la Orden de Trabajo

        </button>

    </h2>

    <div id="accOT"
         class="accordion-collapse collapse show">

        <div class="accordion-body">

            <div class="row g-3">

                <div class="col-md-3">

                    <label class="form-label text-muted small mb-1">
                        OT
                    </label>

                    <div id="rv_numero_ot"
                         class="fw-bold text-primary">
                        -
                    </div>

                </div>

                <div class="col-md-3">

                    <label class="form-label text-muted small mb-1">
                        Cliente
                    </label>

                    <div id="rv_cliente"
                         class="fw-bold">
                        -
                    </div>

                </div>

                <div class="col-md-3">

                    <label class="form-label text-muted small mb-1">
                        Tipo OT
                    </label>

                    <div id="rv_tipo_ot"
                         class="fw-bold">
                        -
                    </div>

                </div>

                <div class="col-md-3">

                    <label class="form-label text-muted small mb-1">
                        Empresa
                    </label>

                    <div id="rv_empresa"
                         class="fw-bold">
                        -
                    </div>

                </div>

            </div>

            <hr>

            <div class="row g-3">

                <div class="col-md-4">

                    <label class="form-label text-muted small mb-1">
                        O.C. Cliente
                    </label>

                    <div id="rv_oc_cliente">
                        -
                    </div>

                </div>

                <div class="col-md-4">

                    <label class="form-label text-muted small mb-1">
                        Tipo Documento
                    </label>

                    <div id="rv_tipo_documento"
                         class="fw-bold">
                        -
                    </div>

                </div>

                <div class="col-md-4">

                    <label class="form-label text-muted small mb-1">
                        Documento Principal
                    </label>

                    <div id="rv_documento_principal"
                         class="fw-bold text-primary">
                        -
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>