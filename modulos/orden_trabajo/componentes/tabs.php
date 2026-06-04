<?php
// archivo: /modulos/orden_trabajo/componentes/tabs.php
?>

<ul class="nav nav-tabs mb-3" id="ordenTabs" role="tablist">

    <li class="nav-item" role="presentation">
        <button 
            class="nav-link active" 
            id="tab-activas"
            data-bs-toggle="tab" 
            data-bs-target="#activas" 
            type="button" 
            role="tab"
            aria-controls="activas"
            aria-selected="true"
        >
            <i class="fas fa-circle text-success me-1"></i> Activas
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button 
            class="nav-link" 
            id="tab-anuladas"
            data-bs-toggle="tab" 
            data-bs-target="#anuladas" 
            type="button" 
            role="tab"
            aria-controls="anuladas"
            aria-selected="false"
        >
            <i class="fas fa-circle text-warning me-1"></i> Anuladas
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button 
            class="nav-link" 
            id="tab-eliminadas"
            data-bs-toggle="tab" 
            data-bs-target="#eliminadas" 
            type="button" 
            role="tab"
            aria-controls="eliminadas"
            aria-selected="false"
        >
            <i class="fas fa-circle text-danger me-1"></i> Eliminadas
        </button>
    </li>

</ul>
