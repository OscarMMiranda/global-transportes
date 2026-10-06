<?php
// ======================================================
// COMPONENTE: viaje_ruta_component.php
// MÓDULO: Viajes
// RESPONSABILIDAD: Ruta operativa del viaje
// GLOBAL 2026
// ======================================================
?>

<div class="accordion-item">

    <h2 class="accordion-header">

        <button class="accordion-button collapsed"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#accRuta">

            <i class="fa-solid fa-route me-2"></i>

            Ruta del Viaje

        </button>

    </h2>

    <div id="accRuta"
         class="accordion-collapse collapse">

        <div class="accordion-body">

            <div class="row g-3">

                <!-- ORIGEN -->

                <div class="col-md-6">

                    <label class="form-label fw-bold">

                        Origen

                    </label>

                    <select id="rv_origen_id"
                            name="origen_id"
                            class="form-select">

                        <option value="">
                            Seleccionar origen
                        </option>

                    </select>

                </div>

                <!-- DESTINO -->

                <div class="col-md-6">

                    <label class="form-label fw-bold">

                        Destino

                    </label>

                    <select id="rv_destino_id"
                            name="destino_id"
                            class="form-select">

                        <option value="">
                            Seleccionar destino
                        </option>

                    </select>

                </div>

            </div>

            <div class="row g-3 mt-1">

                <!-- ZONA -->

                <div class="col-md-6">

                    <label class="form-label fw-bold">

                        Zona

                    </label>

                    <select id="rv_zona_id"
                            name="zona_id"
                            class="form-select">

                        <option value="">
                            Seleccionar zona
                        </option>

                    </select>

                </div>

                <!-- DISTANCIA REFERENCIAL -->

                <div class="col-md-6">

                    <label class="form-label fw-bold">

                        Distancia (Km)

                    </label>

                    <input type="number"
                           id="rv_distancia_km"
                           name="distancia_km"
                           class="form-control"
                           min="0"
                           readonly>

                </div>

            </div>

            <hr>

            <div class="alert alert-info py-2 mb-0">

                <i class="fa-solid fa-circle-info me-2"></i>

                Seleccione el origen y destino principal del viaje.
                La zona y distancia podrán calcularse automáticamente.

            </div>

        </div>

    </div>

</div>