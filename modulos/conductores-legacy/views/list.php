<?php
// views/list.php
// Este snippet sólo define la tabla. El tbody queda vacío para que DataTables
// consuma los datos desde api.php?op=list y los inserte dinámicamente.
?>
<table id="tblConductores" class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>ID</th>
      <th>Apellido, Nombre</th>
      <th>DNI</th>
      <th>Licencia</th>
      <th>Teléfono</th>
      <th>Correo</th>
      <th>Estado</th>
      <th style="width:120px">Acciones</th>
    </tr>
  </thead>
  <tbody>
    <!-- DataTables inyectará aquí cada <tr> con los conductores -->
  </tbody>
</table>
