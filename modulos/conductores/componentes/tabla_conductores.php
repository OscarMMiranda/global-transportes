<?php
/**
 * Tabla de conductores (estructura mínima para DataTables AJAX)
 * Este archivo solo define la estructura de la tabla.
 * El contenido y el <thead> se generan dinámicamente desde conductores.js.
 */
?>

<div class="table-responsive">
  <table id="<?= htmlspecialchars($tablaId) ?>" 
         class="table table-striped table-bordered table-hover table-sm align-middle w-100">
  </table>
</div>