<?php
// ======================================================
// COMPONENTE: editar_resumen.php
// MÓDULO: Órdenes de Trabajo
// RESPONSABILIDAD: Resumen ejecutivo de la OT
// GLOBAL 2026
// ======================================================
?>

<div class="card border-0 shadow-sm mb-3">

    <div class="card-body py-3">

        <div class="row align-items-center">

            <!-- OT -->
            <div class="col-lg-3 col-md-6 mb-2 mb-lg-0">

                <div class="small text-muted text-uppercase">
                    Orden de Trabajo
                </div>

                <div class="fw-bold text-primary fs-5"
                     id="resumen_numero_ot">

                    ---
                </div>

            </div>

            <!-- Estado Operativo -->
            <div class="col-lg-3 col-md-6 mb-2 mb-lg-0">

                <div class="small text-muted text-uppercase">
                    Estado Operativo
                </div>

                <span
                    id="resumen_estado_operativo"
                    class="badge bg-warning text-dark px-3 py-2">

                    Pendiente

                </span>

            </div>

            <!-- Estado Logístico -->
            <div class="col-lg-3 col-md-6 mb-2 mb-lg-0">

                <div class="small text-muted text-uppercase">
                    Estado Logístico
                </div>

                <span
                    id="resumen_estado_logistico"
                    class="badge bg-secondary px-3 py-2">

                    Por Programar

                </span>

            </div>

            <!-- Viajes -->
            <div class="col-lg-3 col-md-6">

                <div class="small text-muted text-uppercase">
                    Viajes Asociados
                </div>

                <div
                    id="resumen_numero_viajes"
                    class="fw-bold fs-5">

                    0

                </div>

            </div>

        </div>

    </div>

</div>