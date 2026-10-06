<?php
// ======================================================
//  VIEW: ver_ot.php
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Vista corporativa para VER OT
//  GLOBAL 2026 — Arquitectura Limpia (Versión 4.7)
// ======================================================

/** @var array $data */

// Normalización corporativa
$tipo = strtoupper(trim($data['tipo_ot_nombre']));
?>

<div class="container-fluid">

    <!-- ========================= -->
    <!-- BLOQUE: Datos principales -->
    <!-- ========================= -->
    <div class="card mb-3 shadow-sm border-0 card-corp">
        <div class="card-header bg-light fw-bold">
            Datos principales
        </div>
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="fw-bold text-muted">Número OT</label>
                    <div class="valor-corp"><?php echo $data['numero_ot']; ?></div>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold text-muted">Fecha</label>
                    <div class="valor-corp"><?php echo $data['fecha']; ?></div>
                </div>

                <div class="col-md-4">
					<label class="fw-bold text-muted">Semana</label>
					<div class="valor-corp">
						<?php echo !empty($data['semana_ot'])
							? $data['semana_ot']
							: '-'; ?>
					</div>                
				</div>
            </div>

        </div>
    </div>

    <!-- ========================= -->
    <!-- BLOQUE: Cliente / Empresa -->
    <!-- ========================= -->
    <div class="card mb-3 shadow-sm border-0 card-corp">
        <div class="card-header bg-light fw-bold">
            Cliente y Empresa
        </div>
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="fw-bold text-muted">Cliente</label>
                    <div class="valor-corp"><?php echo $data['cliente_nombre']; ?></div>
                </div>

                <div class="col-md-6">
                    <label class="fw-bold text-muted">Empresa</label>
                    <div class="valor-corp"><?php echo $data['empresa_nombre']; ?></div>
                </div>
            </div>

        </div>
    </div>

    <!-- ========================= -->
    <!-- BLOQUE: Detalles de la OT -->
    <!-- ========================= -->
    <div class="card mb-3 shadow-sm border-0 card-corp">
        <div class="card-header bg-light fw-bold">
            Detalles de la Orden
        </div>
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="fw-bold text-muted">Tipo de Orden</label>
                    <div class="valor-corp"><?php echo $data['tipo_ot_nombre']; ?></div>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold text-muted">Estado</label>
                    <div class="valor-corp"><?php echo $data['estado_nombre']; ?></div>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold text-muted">OC Cliente</label>
                    <div class="valor-corp"><?php echo $data['oc_cliente']; ?></div>
                </div>
            </div>

            <!-- Campo dinámico según tipo -->
            <div class="row mb-3">
                <div class="col-md-12">

                    <?php if ($tipo === 'IMPORTACION' || $tipo === 'IMPORTACIÓN'): ?>
                        <label class="fw-bold text-muted">Número DAM / DUA</label>
                        <div class="valor-corp"><?php echo $data['numero_dam']; ?></div>
                    <?php endif; ?>

                    <?php if ($tipo === 'EXPORTACION' || $tipo === 'EXPORTACIÓN'): ?>
                        <label class="fw-bold text-muted">Número Booking</label>
                        <div class="valor-corp"><?php echo $data['numero_booking']; ?></div>
                    <?php endif; ?>

                    <?php if ($tipo === 'NACIONAL'): ?>
                        <label class="fw-bold text-muted">Otros</label>
                        <div class="valor-corp"><?php echo $data['otros']; ?></div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>

</div>
