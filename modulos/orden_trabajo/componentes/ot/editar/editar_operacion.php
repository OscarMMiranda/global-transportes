<?php
// ======================================================
// COMPONENTE: editar_operacion.php
// MÓDULO: Órdenes de Trabajo
// RESPONSABILIDAD: Información Operativa
// GLOBAL 2026
// ======================================================
?>

<div class="card border-0 shadow-sm mb-3">

    <div class="card-header bg-light">

        <h6 class="mb-0 fw-bold">

            <i class="fa-solid fa-truck-fast me-2 text-primary"></i>

            Información Operativa

        </h6>

    </div>

    <div class="card-body">

        <div class="row g-3">

            <!-- OC CLIENTE -->

            <div class="col-md-6">

                <label
                    for="editar_oc_cliente"
                    class="form-label fw-bold small">

                    Orden de Compra del Cliente

                </label>

                <input
                    type="text"
                    class="form-control"
                    name="oc_cliente"
                    id="editar_oc_cliente"
                    maxlength="100"
                    autocomplete="off">

            </div>

            <!-- IMPORTACION -->

            <div
                id="campo_importacion"
                class="col-md-6 d-none">

                <label
                    for="editar_numero_dam"
                    class="form-label fw-bold small">

                    Número DAM / DUA

                </label>

                <input
                    type="text"
                    class="form-control"
                    name="numero_dam"
                    id="editar_numero_dam"
                    maxlength="50">

            </div>

            <!-- EXPORTACION -->

            <div
                id="campo_exportacion"
                class="col-md-6 d-none">

                <label
                    for="editar_numero_booking"
                    class="form-label fw-bold small">

                    Número Booking

                </label>

                <input
                    type="text"
                    class="form-control"
                    name="numero_booking"
                    id="editar_numero_booking"
                    maxlength="50">

            </div>

            <!-- NACIONAL -->

            <div
                id="campo_nacional"
                class="col-md-6 d-none">

                <label
                    for="editar_otros"
                    class="form-label fw-bold small">

                    Otros

                </label>

                <input
                    type="text"
                    class="form-control"
                    name="otros"
                    id="editar_otros"
                    maxlength="100">

            </div>

        </div>

    </div>

</div>