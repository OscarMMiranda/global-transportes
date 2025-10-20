<?php
if (!isset($entidad) || !is_array($entidad)) {
    echo "<div class='alert alert-warning'>Entidad no cargada correctamente.</div>";
    return;
}
?>

<!-- ✅ Estilos -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<div class="container-fluid" style="margin-top: 20px;">
  <h4 class="mb-3">
    <i class="fa fa-building"></i> Ficha de entidad: <?= htmlspecialchars($entidad['nombre']) ?>
    <a href="index.php?action=list" class="btn btn-default btn-sm pull-right">
      <i class="fa fa-arrow-left"></i> Volver
    </a>
  </h4>

  <!-- Datos generales -->
  <div class="panel panel-default">
    <div class="panel-heading"><strong><i class="fa fa-id-card"></i> Datos generales</strong></div>
    <div class="panel-body">
      <div class="row">
        <div class="col-md-4"><strong>RUC:</strong> <?= htmlspecialchars($entidad['ruc']) ?></div>
        <div class="col-md-8"><strong>Dirección:</strong> <?= htmlspecialchars($entidad['direccion']) ?></div>
      </div>
    </div>
  </div>

  <!-- Pestañas -->
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tabDatos" data-toggle="tab">📄 Datos</a></li>
    <li><a href="#tabLugares" data-toggle="tab">📍 Lugares</a></li>
  </ul>

  <div class="tab-content" style="border: 1px solid #ddd; padding: 15px;">
    <div class="tab-pane fade in active" id="tabDatos">
      <p class="text-muted">Aquí podrías mostrar más datos, documentos, contactos, etc.</p>
    </div>

    <div class="tab-pane fade" id="tabLugares">
      <!-- ✅ Campo oculto para trazabilidad JS -->
      <input type="hidden" id="entidad_id" value="<?= intval($entidad['id']) ?>">

      <div id="botonAgregarLugar" class="mb-3"></div>

      <table class="table table-bordered table-hover table-striped">
        <thead>
          <tr class="info">
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Distrito</th>
            <th>Dirección</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="bodyLugares">
          <!-- Se llena por AJAX -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ✅ Modal -->
<?php include $_SERVER['DOCUMENT_ROOT'] . '/modulos/mantenimiento/lugares/modal/modal_lugar.php'; ?>

<!-- ✅ Scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="/modulos/mantenimiento/lugares/js/lugares.js"></script>

<script>
  $(document).ready(function () {
    listarLugares($('#entidad_id').val());

    $('#botonAgregarLugar').html(
      '<button class="btn btn-primary" onclick="abrirModalLugar(0)">' +
      '<i class="fa fa-plus"></i> Agregar lugar' +
      '</button>'
    );
  });
</script>