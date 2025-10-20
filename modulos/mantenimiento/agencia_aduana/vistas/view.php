<?php
// archivo: /modulos/mantenimiento/agencia_aduana/vistas/view.php

include __DIR__ . '/layout/header.php';

// ✅ Mensajes flash y errores centralizados
include __DIR__ . '/../componentes/mensajes_flash.php';
?>

<div class="text-end mb-3">
  <button id="btnNuevaAgencia" class="btn btn-primary">
    <i class="fas fa-plus me-1"></i> Nueva agencia
  </button>
</div>

<?php
// ✅ Modal de creación (contenido se carga por AJAX)
include __DIR__ . '/../modales/modal_agregar.php';

// ✅ Modal de visualización (contenido se carga por AJAX)
include __DIR__ . '/../modales/modal_ver.php';

// ✅ Modal de edición (contenido se carga por AJAX)
include __DIR__ . '/../modales/modal_editar.php';
?>

<ul class="nav nav-tabs mb-3" id="tabsAgencias" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="tab-activos" data-bs-toggle="tab" data-bs-target="#panelActivos" type="button" role="tab">
      🟢 Activos
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="tab-inactivos" data-bs-toggle="tab" data-bs-target="#panelInactivos" type="button" role="tab">
      🔴 Eliminados
    </button>
  </li>
</ul>

<div class="tab-content" id="contenedorTabsAgencias">
  <div class="tab-pane fade show active" id="panelActivos" role="tabpanel">
    <div id="contenedorActivos" class="py-3"></div>
  </div>
  <div class="tab-pane fade" id="panelInactivos" role="tabpanel">
    <div id="contenedorInactivos" class="py-3"></div>
  </div>
</div>

<?php
include __DIR__ . '/layout/footer.php';
?>

<!-- ✅ Cargar JS principal para funciones como verAgencia, abrirModalEditar, etc. -->
<script src="/modulos/mantenimiento/agencia_aduana/js/agencia_aduanas.js"></script>