<?php
// archivo: /modulos/orden_trabajo/componentes/tabs.php
?>

<ul class="nav nav-tabs mb-3 shadow-sm" id="ordenTabs" role="tablist">

    <!-- TAB: ACTIVAS -->
    <li class="nav-item" role="presentation">
        <button 
            class="nav-link active fw-semibold"
            id="tab-activas"
            data-bs-toggle="tab"
            data-bs-target="#activas"
            type="button"
            role="tab"
            aria-controls="activas"
            aria-selected="true"
        >
            <i class="fa-solid fa-check text-success me-1"></i>
            Activas
        </button>
    </li>

    <!-- TAB: ANULADAS -->
    <li class="nav-item" role="presentation">
        <button 
            class="nav-link fw-semibold"
            id="tab-anuladas"
            data-bs-toggle="tab"
            data-bs-target="#anuladas"
            type="button"
            role="tab"
            aria-controls="anuladas"
            aria-selected="false"
        >
            <i class="fa-solid fa-ban text-warning me-1"></i>
            Anuladas
        </button>
    </li>

    <!-- TAB: ELIMINADAS -->
    <li class="nav-item" role="presentation">
        <button 
            class="nav-link fw-semibold"
            id="tab-eliminadas"
            data-bs-toggle="tab"
            data-bs-target="#eliminadas"
            type="button"
            role="tab"
            aria-controls="eliminadas"
            aria-selected="false"
        >
            <i class="fa-solid fa-trash text-danger me-1"></i>
            Eliminadas
        </button>
    </li>

</ul>
