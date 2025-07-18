<?php
include_once '../../includes/header.php';
include_once 'form_asignacion.php'; // solo el modal
?>

<h1 class="mb-4">Asignaciones – Conductores / Tractos / Carretas</h1>

<button class="btn btn-success mb-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAsignar">
  <i class="fas fa-plus me-1"></i> Nueva asignación
</button>

<table id="tablaAsignaciones"
       class="table table-bordered table-striped table-hover align-middle text-center">
  <thead class="table-dark">
    <tr>
      <th>Conductor</th><th>Tracto</th><th>Carreta</th>
      <th>Inicio</th><th>Fin</th><th>Estado</th><th>Acción</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>

<script>
  window.ASIGNACIONES_API_URL = '/modulos/asignaciones/api.php';
</script>
<script src="asignaciones.js"></script>

<?php include_once '../../includes/footer.php'; ?>
