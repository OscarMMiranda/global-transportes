<?php
	// archivo: /modulos/empleados/componentes/tabs.php

	$volverA = isset($volverA) ? $volverA : '../../../paneles/admin/vistas/dashboard.php';
	$textoVolver = isset($textoVolver) ? $textoVolver : 'Volver al módulo anterior';
?>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body d-flex justify-content-between align-items-center">

        <a href="<?= htmlspecialchars($volverA) ?>" 
           class="btn btn-outline-dark btn-sm">
            <i class="fa-solid fa-arrow-left me-2"></i>
            <?= htmlspecialchars($textoVolver) ?>
        </a>

        <button type="button" 
                class="btn btn-success btn-sm"
                data-bs-toggle="modal" 
                data-bs-target="#modalEmpleado">
            <i class="fa-solid fa-user-plus me-2"></i>
            Nuevo Empleado
        </button>

    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body p-2">

        <ul class="nav nav-tabs nav-tabs-clean border-0" id="tabsEmpleados" role="tablist">

            <li class="nav-item" role="presentation">
                <button class="nav-link active px-4 py-2 fw-semibold"
                        id="tab-activos"
                        data-bs-toggle="tab"
                        data-bs-target="#panel-activos"
                        type="button"
                        role="tab">

                    <i class="fa-solid fa-user-check text-success me-2"></i>
                    Activos
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link px-4 py-2 fw-semibold"
                        id="tab-inactivos"
                        data-bs-toggle="tab"
                        data-bs-target="#panel-inactivos"
                        type="button"
                        role="tab">

                    <i class="fa-solid fa-user-slash text-secondary me-2"></i>
                    Inactivos
                </button>
            </li>

        </ul>

    </div>
</div>

<div class="tab-content mt-3" id="panelesEmpleados">

    <div class="tab-pane show active" id="panel-activos" role="tabpanel">
        <?php $tablaId = 'tblActivos'; include __DIR__ . '/tabla_empleados.php'; ?>
    </div>

    <div class="tab-pane" id="panel-inactivos" role="tabpanel">
        <?php $tablaId = 'tblInactivos'; include __DIR__ . '/tabla_empleados.php'; ?>
    </div>

</div>