<?php
// ======================================================
// COMPONENTE: editar_general.php
// MÓDULO: Órdenes de Trabajo
// RESPONSABILIDAD: Información general de la OT
// GLOBAL 2026
// ======================================================
?>

<div class="card border-0 shadow-sm mb-3">

    <div class="card-header bg-light">

        <h6 class="mb-0 fw-bold">

            <i class="fa-solid fa-file-lines me-2 text-primary"></i>

            Información General

        </h6>

    </div>

    <div class="card-body">

        <div class="row g-3">

            <!-- NÚMERO OT -->
            <div class="col-lg-3 col-md-6">

                <label class="form-label fw-bold small">

                    Número OT

                </label>

                <input
                    type="text"
                    class="form-control text-center fw-bold text-primary"
                    name="numero_ot"
                    id="editar_numero_ot">

            </div>

            <!-- TIPO OT -->
            <div class="col-lg-3 col-md-6">

                <label class="form-label fw-bold small">

                    Tipo de Operación

                </label>

                <select
                    class="form-select"
                    name="tipo_ot_id"
                    id="editar_tipo_ot">

                    <option value="">
                        Seleccione...
                    </option>

                </select>

            </div>

            <!-- FECHA -->
            <div class="col-lg-3 col-md-6">

                <label class="form-label fw-bold small">

                    Fecha

                </label>

                <input
                    type="date"
                    class="form-control"
                    name="fecha"
                    id="editar_fecha">

            </div>

            <!-- SEMANA -->
            <div class="col-lg-3 col-md-6">

                <label class="form-label fw-bold small">

                    Semana Operativa

                </label>

                <input
                    type="text"
                    class="form-control text-center bg-light fw-bold"
                    name="semana_ot"
                    id="editar_semana_ot"
                    readonly>

            </div>

        </div>

    </div>

</div>