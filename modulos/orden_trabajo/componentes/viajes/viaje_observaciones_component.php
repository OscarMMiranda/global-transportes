<?php
// ======================================================
// COMPONENTE: viaje_observaciones_component.php
// MÓDULO: Viajes
// RESPONSABILIDAD: Observaciones operativas del viaje
// GLOBAL 2026
// ======================================================
?>

<div class="accordion-item">

    <h2 class="accordion-header">

        <button class="accordion-button collapsed"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#accObservaciones">

            <i class="fa-solid fa-note-sticky me-2"></i>

            Observaciones

        </button>

    </h2>

    <div id="accObservaciones"
         class="accordion-collapse collapse">

        <div class="accordion-body">

            <div class="row">

                <div class="col-12">

                    <label class="form-label fw-bold">

                        Observaciones Operativas

                    </label>

                    <textarea
                        id="rv_observaciones"
                        name="observaciones"
                        class="form-control"
                        rows="4"
                        maxlength="1000"
                        placeholder="Ingrese observaciones relevantes del servicio, coordinaciones especiales, restricciones de acceso, horarios, requerimientos del cliente, etc."></textarea>

                    <small class="text-muted">

                        Campo opcional.

                    </small>

                </div>

            </div>

        </div>

    </div>

</div>