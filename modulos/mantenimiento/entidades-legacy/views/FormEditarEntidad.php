<?php
// archivo: /modulos/mantenimiento/entidades/views/FormEditarEntidad.php

/**
 * Escapa valores para el value de inputs y selected.
 */
function valor($entidad, $campo) {
    return $entidad && isset($entidad[$campo])
        ? htmlspecialchars($entidad[$campo])
        : '';
}

// El controlador debe haber definido antes de incluir esta vista:
//   $departamentos = getDepartamentos();
//   // Si es nuevo:
//   //   $depId  = 15;   // Lima
//   //   $provId = 127;  // Provincia Lima
//   //   $distId = 1251; // Distrito Lima
//   // Si es edición, extrae de $entidad sus IDs.
//   $provincias = getProvincias($depId);
//   $distritos  = getDistritos($provId);
//   $tipos      = getTipos();
//   $entidad    = null | array(datos de la entidad)
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= $entidad ? 'Editar entidad #'.$entidad['id'] : 'Registrar nueva entidad' ?></title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="../assets/css/entidades.css">
</head>
<body>
  <div class="container">
    <div class="panel panel-info">
      <div class="panel-heading">
        <strong>
          <i class="fa fa-pencil"></i>
          <?= $entidad
               ? 'Editar entidad #'.$entidad['id']
               : 'Registrar nueva entidad' ?>
        </strong>
      </div>
      <div class="panel-body">
        <form id="formEntidad" method="POST" class="form-horizontal">
          <input type="hidden" name="id" value="<?= valor($entidad, 'id') ?>">

          <!-- Nombre -->
          <div class="form-group">
            <label class="control-label col-sm-2">Nombre:</label>
            <div class="col-sm-10">
              <input type="text"
                     name="nombre"
                     class="form-control"
                     required
                     value="<?= valor($entidad, 'nombre') ?>">
            </div>
          </div>

          <!-- RUC -->
          <div class="form-group">
            <label class="control-label col-sm-2">RUC:</label>
            <div class="col-sm-10">
              <input type="text"
                     name="ruc"
                     maxlength="11"
                     class="form-control"
                     value="<?= valor($entidad, 'ruc') ?>">
            </div>
          </div>

          <!-- Dirección -->
          <div class="form-group">
            <label class="control-label col-sm-2">Dirección:</label>
            <div class="col-sm-10">
              <input type="text"
                     name="direccion"
                     class="form-control"
                     value="<?= valor($entidad, 'direccion') ?>">
            </div>
          </div>

          <!-- Departamento -->
          <div class="form-group">
            <label class="control-label col-sm-2">Departamento:</label>
            <div class="col-sm-10">
              <select name="departamento_id"
                      id="departamento"
                      class="form-control">
                <option value="">-- Seleccionar --</option>
                <?php foreach ($departamentos as $d): ?>
                  <option value="<?= $d['id'] ?>"
                    <?= $d['id'] == $depId ? 'selected' : '' ?>>
                    <?= htmlspecialchars($d['nombre']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <!-- Provincia -->
          <div class="form-group">
            <label class="control-label col-sm-2">Provincia:</label>
            <div class="col-sm-10">
              <select name="provincia_id"
                      id="provincia"
                      class="form-control"
					  data-valor="<?= $provId ?>">
                <option value="">-- Seleccionar --</option>
                <?php foreach ($provincias as $p): ?>
                  <option value="<?= $p['id'] ?>"
                    <?= $p['id'] == $provId ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['nombre']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <!-- Distrito -->
          <div class="form-group">
            <label class="control-label col-sm-2">Distrito:</label>
            <div class="col-sm-10">
              <select name="distrito_id"
                      id="distrito"
                      class="form-control"
					  data-valor="<?= $distId ?>">
                <option value="">-- Seleccionar --</option>
                <?php foreach ($distritos as $d): ?>
                  <option value="<?= $d['id'] ?>"
                    <?= $d['id'] == $distId ? 'selected' : '' ?>>
                    <?= htmlspecialchars($d['nombre']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <!-- Tipo de lugar -->
          <div class="form-group">
            <label class="control-label col-sm-2">Tipo de lugar:</label>
            <div class="col-sm-10">
              <select name="tipo_id"
                      class="form-control"
                      required>
                <option value="">-- Seleccionar --</option>
                <?php foreach ($tipos as $t): ?>
                  <option value="<?= $t['id'] ?>"
                    <?= $entidad && $entidad['tipo_id']==$t['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['nombre']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <!-- Botones -->
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10 text-right">
              <button type="submit" class="btn btn-success">
                <i class="fa fa-save"></i> Guardar
              </button>
              <a href="/modulos/mantenimiento/entidades/index.php"
                 class="btn btn-default">
                <i class="fa fa-times"></i> Cancelar
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal de confirmación -->
  <div class="modal fade"
       id="modalConfirmacion"
       tabindex="-1"
       data-backdrop="static"
       data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmación</h5>
        </div>
        <div class="modal-body"></div>
        <div class="modal-footer">
          <button type="button"
                  class="btn btn-secondary"
                  data-dismiss="modal">Cerrar</button>
          <a href="/modulos/mantenimiento/entidades/index.php"
             class="btn btn-primary">Ir al listado</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../assets/js/entidades.js"></script>
	<script>
  // Precarga dinámica si combos están vacíos en modo edición
  $(document).ready(function () {
    const departamentoId = '<?= $depId ?>';
    const provinciaId    = '<?= $provId ?>';
    const distritoId     = '<?= $distId ?>';

    const provinciaComboVacio = $('#provincia option').length <= 1;
    const distritoComboVacio  = $('#distrito option').length <= 1;

    if (provinciaComboVacio) {
      cargarProvincias(departamentoId, function () {
        $('#provincia').val(provinciaId).trigger('change');
        setTimeout(function () {
          $('#distrito').val(distritoId);
        }, 300);
      });
    } else if (distritoComboVacio) {
      $('#provincia').val(provinciaId).trigger('change');
      setTimeout(function () {
        $('#distrito').val(distritoId);
      }, 300);
    } else {
      $('#provincia').val(provinciaId);
      $('#distrito').val(distritoId);
    }
  });
</script>


  <script>
    // Manejo de envío AJAX del formulario
    $('#formEntidad').on('submit', function(e) {
      e.preventDefault();
      $.post(
        '/modulos/mantenimiento/entidades/controllers/ActualizarEntidad.php',
        $(this).serialize(),
        function(res) {
          if (res.estado === 'ok') {
            $('#modalConfirmacion .modal-body').html(`
              <strong>${res.mensaje}</strong><br>
              <small>ID: ${res.id}
                     | Usuario: ${res.usuario}
                     | ${res.fecha}</small>
            `);
            $('#modalConfirmacion').modal('show');
            setTimeout(function() {
              window.location.href = '/modulos/mantenimiento/entidades/index.php';
            }, 2500);
          } else {
            alert('❌ ' + res.mensaje);
          }
        },
        'json'
      );
    });
  </script>
</body>
</html>