<?php
// archivo: componentes/tipo_mercaderia/tabs.php
// propósito: navegación por pestañas para listar tipos de mercadería activos e inactivos
?>

<!-- Navegación por pestañas -->
<ul class="nav nav-tabs mb-3" id="tabTipos">
  <li class="nav-item">
    <button 
      class="nav-link active" 
      data-bs-toggle="tab" 
      data-bs-target="#activos"
      type="button"
      title="Ver tipos de mercadería activos"
    >
      Activos
    </button>
  </li>
  <li class="nav-item">
    <button 
      class="nav-link" 
      data-bs-toggle="tab" 
      data-bs-target="#inactivos"
      type="button"
      title="Ver tipos de mercadería inactivos"
    >
      Inactivos
    </button>
  </li>
</ul>