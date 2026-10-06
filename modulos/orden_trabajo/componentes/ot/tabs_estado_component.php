<?php
// ======================================================
//  	COMPONENTE		: 	tabs_estado_component.php
//  	MÓDULO			: 	Órdenes de Trabajo (OT)
//  	RESPONSABILIDAD	: 	Tabs corporativos de estado OT
//  	GLOBAL 2026 — Arquitectura Limpia (Optimizado Visual)
// 		CAPA 			: 	COMPONENTE VISUAL
// 		ESTADO 			: 	ACTIVO
// 		NO ELIMINAR
// ======================================================

/**
 * @var array $data
 * $data['estado_sel'] → ID del estado seleccionado
 */
?>

<section class="ot-tabs-estado mb-3"
         data-componente="tabs_estado"
         data-version="4.1">

    <ul class="nav nav-tabs nav-tabs-corp px-2">

        <!-- TODAS -->
        <li class="nav-item">
            <button class="nav-link ot-tab-estado px-3
                <?php echo ($data['estado_sel'] === 'TODAS') ? 'active' : ''; ?>"
                data-estado="TODAS">
                <i class="fa-solid fa-list fa-fw me-1"></i>
                Todas
            </button>
        </li>

        <!-- PENDIENTE -->
        <li class="nav-item">
            <button class="nav-link ot-tab-estado px-3
                <?php echo ($data['estado_sel'] == 1) ? 'active' : ''; ?>"
                data-estado="1">
                <i class="fa-solid fa-hourglass-start fa-fw me-1"></i>
                Pendiente
            </button>
        </li>

        <!-- EN PROCESO -->
        <li class="nav-item">
            <button class="nav-link ot-tab-estado px-3
                <?php echo ($data['estado_sel'] == 2) ? 'active' : ''; ?>"
                data-estado="2">
                <i class="fa-solid fa-gears fa-fw me-1"></i>
                En proceso
            </button>
        </li>

        <!-- COMPLETADA -->
        <li class="nav-item">
            <button class="nav-link ot-tab-estado px-3
                <?php echo ($data['estado_sel'] == 3) ? 'active' : ''; ?>"
                data-estado="3">
                <i class="fa-solid fa-check fa-fw me-1"></i>
                Completada
            </button>
        </li>

        <!-- FACTURADA -->
        <li class="nav-item">
            <button class="nav-link ot-tab-estado px-3
                <?php echo ($data['estado_sel'] == 4) ? 'active' : ''; ?>"
                data-estado="4">
                <i class="fa-solid fa-file-invoice-dollar fa-fw me-1"></i>
                Facturada
            </button>
        </li>

        <!-- CANCELADA -->
        <li class="nav-item">
            <button class="nav-link ot-tab-estado px-3
                <?php echo ($data['estado_sel'] == 5) ? 'active' : ''; ?>"
                data-estado="5">
                <i class="fa-solid fa-ban fa-fw me-1"></i>
                Cancelada
            </button>
        </li>

        <!-- OBSERVADA -->
        <li class="nav-item">
            <button class="nav-link ot-tab-estado px-3
                <?php echo ($data['estado_sel'] == 6) ? 'active' : ''; ?>"
                data-estado="6">
                <i class="fa-solid fa-eye fa-fw me-1"></i>
                Observada
            </button>
        </li>

        <!-- ANULADA -->
        <li class="nav-item">
            <button class="nav-link ot-tab-estado px-3
                <?php echo ($data['estado_sel'] == 7) ? 'active' : ''; ?>"
                data-estado="7">
                <i class="fa-solid fa-xmark fa-fw me-1"></i>
                Anulada
            </button>
        </li>

        <!-- ELIMINADA -->
        <li class="nav-item">
            <button class="nav-link ot-tab-estado px-3
                <?php echo ($data['estado_sel'] == 8) ? 'active' : ''; ?>"
                data-estado="8">
                <i class="fa-solid fa-trash fa-fw me-1"></i>
                Eliminada
            </button>
        </li>

    </ul>

</section>
