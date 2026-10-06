<?php
// ======================================================
// COMPONENTE: editar_cliente_empresa.php
// MÓDULO: Órdenes de Trabajo
// RESPONSABILIDAD: Cliente y Empresa
// GLOBAL 2026
// ======================================================
?>

<div class="card border-0 shadow-sm mb-3">

    <div class="card-header bg-light">

        <h6 class="mb-0 fw-bold">

            <i class="fa-solid fa-building-user me-2 text-primary"></i>

            Cliente y Empresa

        </h6>

    </div>

    <div class="card-body">

        <div class="row g-3">

            <!-- CLIENTE -->
            <div class="col-lg-7 col-md-12">

                <label
                    for="editar_cliente_id"
                    class="form-label fw-bold small">

                    Cliente

                </label>

                <select
                    class="form-select"
                    name="cliente_id"
                    id="editar_cliente_id">

                    <option value="">
                        Seleccione...
                    </option>

                </select>

            </div>

            <!-- EMPRESA -->
            <div class="col-lg-5 col-md-12">

                <label
                    for="editar_empresa_id"
                    class="form-label fw-bold small">

                    Empresa

                </label>

                <select
                    class="form-select"
                    name="empresa_id"
                    id="editar_empresa_id">

                    <option value="">
                        Seleccione...
                    </option>

                </select>

            </div>

        </div>

    </div>

</div>