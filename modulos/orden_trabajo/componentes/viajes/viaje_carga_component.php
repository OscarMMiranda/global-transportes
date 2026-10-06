<?php
// ======================================================
// COMPONENTE: viaje_carga_component.php
// MÓDULO: Viajes
// RESPONSABILIDAD: Información de carga
// GLOBAL 2026
// ======================================================
?>

<div class="accordion-item">

    <h2 class="accordion-header">

        <button class="accordion-button collapsed"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#accCarga">

            <i class="fa-solid fa-box me-2"></i>

            Información de Carga

        </button>

    </h2>

    <div id="accCarga"
         class="accordion-collapse collapse">

        <div class="accordion-body">

            <!-- ===================================== -->
            <!-- TIPO DE CARGA -->
            <!-- ===================================== -->

            <div class="row g-3">

                <div class="col-md-4">

                    <label class="form-label fw-bold">

                        Tipo de Carga

                    </label>

                    <select id="rv_tipo_carga"
                            name="tipo_carga"
                            class="form-select">

                        <option value="">
                            Seleccionar
                        </option>

                        <option value="CONTENEDOR">
                            Contenedor
                        </option>

                        <option value="MERCADERIA">
                            Mercadería
                        </option>

                    </select>

                </div>

                <div class="col-md-4">

                    <label class="form-label fw-bold">

                        Peso Bruto (Kg)

                    </label>

                    <input type="number"
                           id="rv_peso_bruto"
                           name="peso_bruto"
                           class="form-control">

                </div>

                <div class="col-md-4">

                    <label class="form-label fw-bold">

                        Peso Neto (Kg)

                    </label>

                    <input type="number"
                           id="rv_peso_neto"
                           name="peso_neto"
                           class="form-control">

                </div>

            </div>

            <!-- ===================================== -->
            <!-- CONTENEDOR -->
            <!-- ===================================== -->

            <div id="bloqueContenedor"
                 class="mt-4 d-none">

                <div class="border rounded p-3 bg-light">

                    <h6 class="fw-bold mb-3">

                        Datos del Contenedor

                    </h6>

                    <div class="row g-3">

                        <div class="col-md-4">

                            <label class="form-label fw-bold">

                                Tipo Contenedor

                            </label>

                            <select id="rv_contenedor_tipo_id"
                                    name="contenedor_tipo_id"
                                    class="form-select">

                                <option value="">
                                    Seleccionar
                                </option>

                            </select>

                        </div>

                        <div class="col-md-4">

                            <label class="form-label fw-bold">

                                N° Contenedor

                            </label>

                            <input type="text"
                                   id="rv_contenedor_numero"
                                   name="contenedor_numero"
                                   class="form-control"
                                   maxlength="15"
                                   placeholder="FCIU2829454">

                        </div>

                        <div class="col-md-4">

                            <label class="form-label fw-bold">

                                Contenedor

                            </label>

                            <input type="text"
                                   id="rv_contenedor_descripcion"
                                   class="form-control bg-light"
                                   readonly
                                   placeholder="45G1 HIGH CUBE">

                        </div>

                    </div>

                </div>

            </div>

            <!-- ===================================== -->
            <!-- MERCADERIA -->
            <!-- ===================================== -->

            <div id="bloqueMercaderia"
                 class="mt-4 d-none">

                <div class="border rounded p-3 bg-light">

                    <h6 class="fw-bold mb-3">

                        Datos de la Mercadería

                    </h6>

                    <div class="row g-3">

                        <div class="col-md-4">

                            <label class="form-label fw-bold">

                                Tipo Mercadería

                            </label>

                            <select id="rv_mercaderia_tipo_id"
                                    name="mercaderia_tipo_id"
                                    class="form-select">

                                <option value="">
                                    Seleccionar
                                </option>

                            </select>

                        </div>

                        <div class="col-md-8">

                            <label class="form-label fw-bold">

                                Descripción de Carga

                            </label>

                            <input type="text"
                                   id="rv_descripcion_carga"
                                   name="descripcion_carga"
                                   class="form-control"
                                   placeholder="ROLLOS DE ACERO, PALLETS, TUBOS PVC, COBRE, ETC.">

                        </div>

                    </div>

                </div>

            </div>

            <!-- ===================================== -->
            <!-- INFO -->
            <!-- ===================================== -->

            <div class="alert alert-warning mt-3 mb-0">

                <i class="fa-solid fa-triangle-exclamation me-2"></i>

                Si la carga es un contenedor, se solicitará el tipo y número de contenedor.
                Si es mercadería suelta, se solicitará la descripción de la carga transportada.

            </div>

        </div>

    </div>

</div>