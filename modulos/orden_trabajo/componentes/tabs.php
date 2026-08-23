<?php
// ======================================================
//  ARCHIVO: /modulos/orden_trabajo/componentes/tabs.php
//  RESPONSABILIDAD: Tabs dinámicos para DataTables
// ======================================================
?>

<ul class="nav nav-tabs mb-3" id="tabsOT">

    <li class="nav-item">
        <button class="nav-link active fw-semibold" data-estado="ACTIVA">
            <i class="fa-solid fa-check text-success me-1"></i>
            Activas
        </button>
    </li>

    <li class="nav-item">
        <button class="nav-link fw-semibold" data-estado="ANULADA">
            <i class="fa-solid fa-ban text-warning me-1"></i>
            Anuladas
        </button>
    </li>

    <li class="nav-item">
        <button class="nav-link fw-semibold" data-estado="ELIMINADA">
            <i class="fa-solid fa-trash text-danger me-1"></i>
            Eliminadas
        </button>
    </li>

</ul>
