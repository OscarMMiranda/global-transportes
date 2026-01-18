<?php
// archivo: /modulos/conductores/componentes/tabs.php

$volverA = isset($volverA) ? $volverA : '../../../paneles/admin/vistas/dashboard.php';
$textoVolver = isset($textoVolver) ? $textoVolver : 'Volver al módulo anterior';
?>

<div class="d-flex justify-content-between align-items-center mb-3">

    <a href="<?= htmlspecialchars($volverA) ?>" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>
        <?= htmlspecialchars($textoVolver) ?>
    </a>

    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalConductor">
        <i class="fa-solid fa-plus me-1"></i>
        Nuevo conductor
    </button>
</div>

<?php include __DIR__ . '/../../includes/mensajes_flash.php'; ?>

<ul class="nav nav-tabs nav-tabs-clean" id="tabsConductores" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="tab-activos"
            data-bs-toggle="tab" data-bs-target="#panel-activos"
            type="button" role="tab">
            <i class="fa-solid fa-user-check text-success me-2"></i>
            <span class="fw-bold">Activos</span>
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-inactivos"
            data-bs-toggle="tab" data-bs-target="#panel-inactivos"
            type="button" role="tab">
            <i class="fa-solid fa-user-slash text-secondary me-2"></i>
            <span class="fw-bold">Inactivos</span>
        </button>
    </li>
</ul>

<div class="tab-content mt-3" id="panelesConductores">

    <div class="tab-pane show active" id="panel-activos" role="tabpanel">
        <?php $tablaId = 'tblActivos'; include __DIR__ . '/tabla_conductores.php'; ?>
    </div>

    <div class="tab-pane" id="panel-inactivos" role="tabpanel">
        <?php $tablaId = 'tblInactivos'; include __DIR__ . '/tabla_conductores.php'; ?>
    </div>

</div>