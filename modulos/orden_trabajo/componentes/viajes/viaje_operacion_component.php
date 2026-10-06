<?php
// ======================================================
// COMPONENTE: viaje_operacion_component.php
// MÓDULO: Viajes
// RESPONSABILIDAD: Datos operativos del viaje
// GLOBAL 2026
// ======================================================
?>

<div class="accordion-item">

    <h2 class="accordion-header">

        <button class="accordion-button collapsed"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#accOperacion">

            <i class="fa-solid fa-truck-fast me-2"></i>

            Operación y Asignación

        </button>

    </h2>

    <div id="accOperacion"
         class="accordion-collapse collapse">

        <div class="accordion-body">

            <div class="row g-3">

                <!-- FECHA -->

                <div class="col-md-4">

                    <label class="form-label fw-bold">

                        Fecha Viaje

                    </label>

                    <input type="date"
                           id="rv_fecha_viaje"
                           class="form-control">

                    <small class="text-muted">

                        Se carga automáticamente con la fecha de la OT.

                    </small>

                </div>

                <!-- VEHICULO -->

                <div class="col-md-4">

                    <label class="form-label fw-bold">

                        Vehículo

                    </label>

                    <select id="rv_vehiculo_id"
                            class="form-select">

                        <option value="">
                            Seleccionar vehículo
                        </option>

                    </select>

                </div>

                <!-- CONDUCTOR -->

                <div class="col-md-4">

                    <label class="form-label fw-bold">

                        Conductor Asociado

                    </label>

                    <input type="text"
                           id="rv_conductor_nombre"
                           class="form-control bg-light"
                           readonly>

                    <input type="hidden"
                           id="rv_conductor_id">

                </div>

            </div>

            <hr>

            <div class="alert alert-light mb-0">

                <i class="fa-solid fa-circle-info me-2"></i>

                Al seleccionar una unidad, el conductor asignado
                se mostrará automáticamente.

            </div>

        </div>

    </div>

</div>