<?php
/**
 * Tabla de conductores (estructura mínima para DataTables AJAX)
 * Este archivo solo define la estructura de la tabla.
 * El contenido (thead y tbody) se genera dinámicamente desde datatables.js.
 *
 * Uso:
 *   $tablaId = 'tblActivos';
 *   include __DIR__ . '/tabla_conductores.php';
 *
 *   $tablaId = 'tblInactivos';
 *   include __DIR__ . '/tabla_conductores.php';
 */
?>

<div class="table-responsive">
  <table id="<?= htmlspecialchars($tablaId, ENT_QUOTES, 'UTF-8') ?>"
         class="table table-striped table-bordered table-hover table-sm align-middle w-100">
  </table>
</div>