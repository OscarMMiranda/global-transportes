<?php
// archivo: /modulos/mantenimiento/entidades/controllers/ListController.php
// Visual moderno con pestañas activas/inactivas y trazabilidad total

ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1) Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');

// 2) Cargar configuración y conexión
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

// 3) Validar conexión
if (!is_object($conn) || get_class($conn) !== 'mysqli') {
    error_log("❌ Conexión no disponible en ListController.php");
    echo "<div class='alert alert-danger'><i class='fa fa-exclamation-triangle'></i> Error de conexión con la base de datos.</div>";
    ob_end_flush();
    return;
}

// 4) Cargar helpers
include_once dirname(__FILE__) . '/../helpers/EntidadesHelpers.php';
if (!function_exists('renderListadoEntidades')) {
    echo "<div class='alert alert-danger'>❌ La función renderListadoEntidades no está disponible.</div>";
    ob_end_flush();
    return;
}
?>

<!-- Bootstrap y estilos -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="/assets/custom/styles.css">

<!-- ✅ Alertas visuales -->
<?php
$alertas = array(
    'error'      => array('conexion', 'danger', 'fa-ban', 'Error de conexión con la base de datos.'),
    'restaurado' => array('ok', 'success', 'fa-check', 'Entidad restaurada correctamente.'),
    'borrado'    => array('ok', 'warning', 'fa-minus-circle', 'Entidad marcada como inactiva.'),
    'eliminado'  => array('ok', 'danger', 'fa-trash', 'Entidad eliminada permanentemente.'),
    'creado'     => array('ok', 'success', 'fa-plus-circle', 'Entidad creada correctamente.')
);

foreach ($alertas as $key => $config) {
    list($valor, $tipo, $icono, $mensaje) = $config;
    if (isset($_GET[$key]) && $_GET[$key] === $valor) {
        echo "<div class='alert alert-$tipo'><i class='fa $icono'></i> $mensaje</div>";
    }
}
?>

<div class="panel panel-primary" style="margin-top: 20px;">
  <div class="panel-heading clearfix">
    <div class="pull-left">
      <h4 class="panel-title"><i class="fa fa-building"></i> Entidades registradas</h4>
    </div>
    <div class="pull-right">
      <div class="btn-group">
        <a href="../mantenimiento.php" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Regresar</a>
        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalEntidad"><i class="fa fa-plus-circle"></i> Nueva entidad</button>
      </div>
    </div>
  </div>

  <div class="panel-body">
    <ul class="nav nav-tabs" role="tablist">
      <li class="active"><a href="#tabActivos" role="tab" data-toggle="tab"><i class="fa fa-check-circle text-success"></i> Activos</a></li>
      <li><a href="#tabInactivos" role="tab" data-toggle="tab"><i class="fa fa-ban text-danger"></i> Inactivos</a></li>
    </ul>

    <div class="tab-content" style="margin-top: 15px;">
      <div class="tab-pane active" id="tabActivos"><?php renderListadoEntidades($conn, 'activo'); ?></div>
      <div class="tab-pane" id="tabInactivos"><?php renderListadoEntidades($conn, 'inactivo'); ?></div>
    </div>
  </div>
</div>

<!-- ✅ Modales externos -->
<?php
// $modalCrearPath = $_SERVER['DOCUMENT_ROOT'] . '/modulos/mantenimiento/entidades/views/ModalCrearEntidad.php';
$modalCrearPath = dirname(__FILE__) . '/../views/ModalCrearEntidad.php';
if (is_file($modalCrearPath)) {
    include_once $modalCrearPath;
} else {
    echo "<div class='alert alert-danger'>❌ No se encontró el archivo ModalCrearEntidad.php en <code>$modalCrearPath</code></div>";
}

$modalVerPath = dirname(__FILE__) . '/../views/ModalVerEntidad.php';
if (is_file($modalVerPath)) {
    include_once $modalVerPath;
} else {
    echo "<div class='alert alert-danger'>❌ No se encontró el archivo ModalVerEntidad.php en <code>$modalVerPath</code></div>";
}
?>

<!-- Scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<script>
function verEntidad(id) {
  $.get('ver.php', {id: id}, function(html) {
    $('#modalContenidoVer').html(html);
    $('#modalVerEntidad').modal('show');
  }).fail(function(jqXHR, textStatus, errorThrown) {
    var mensaje = 'Error: ' + errorThrown + ' (' + textStatus + ')';
    $('#modalContenidoVer').html('<div class="modal-body"><div class="alert alert-danger">' + mensaje + '</div></div>');
    $('#modalVerEntidad').modal('show');
  });
}

function crearEntidad() {
  var datos = $('#formCrearEntidad').serialize();
  $.post('../actions/crear.php', datos, function(respuesta) {
    if (respuesta === 'ok') {
      window.location.href = 'ListController.php?creado=ok';
    } else {
      $('#modalEntidad .modal-body').prepend('<div class="alert alert-danger'>Error: ' + respuesta + '</div>');
    }
  }).fail(function() {
    $('#modalEntidad .modal-body').prepend('<div class="alert alert-danger'>Error al enviar datos.</div>');
  });
}

// ✅ Carga jerárquica y tipos
$('#modalEntidad').on('shown.bs.modal', function () {
  $.get('../ajax/tipos.php', function(html) {
    $('#tipo_id').html(html);
  });
  $.get('../ajax/departamentos.php', function(html) {
    $('#departamento_id').html(html);
  });
});

$('#departamento_id').change(function() {
  var id = $(this).val();
  $.get('../ajax/provincias.php', {departamento_id: id}, function(html) {
    $('#provincia_id').html(html);
    $('#distrito_id').html('<option value="">-- Seleccionar --</option>');
  });
});

$('#provincia_id').change(function() {
  var id = $(this).val();
  $.get('../ajax/distritos.php', {provincia_id: id}, function(html) {
    $('#distrito_id').html(html);
  });
});
</script>

<?php ob_end_flush(); ?>