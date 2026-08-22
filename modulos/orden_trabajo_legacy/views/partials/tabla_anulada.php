<?php
// archivo: /modulos/orden_trabajo/views/partials/tabla_anulada.php
?>

<div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
  <table id="tablaOrdenesAnuladas" class="table table-sm table-hover table-striped align-middle shadow-sm tabla-ordenes">
    <thead class="table-dark sticky-top">
      <tr class="text-center">
        <th>Número OT</th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>O.C.</th>
        <th>Tipo OT</th>
        <th>Empresa</th>
        <th>Estado</th>
        <th class="text-center">Acciones</th>
      </tr>
    </thead>

    <tbody id="tbodyAnuladas">
      <!-- AJAX insertará aquí -->
    </tbody>

  </table>
</div>
