<?php
// archivo: /modulos/mantenimiento/entidades/controllers/ListController.php
// Visual principal con pestañas, modales y trazabilidad total

ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🔧 Modo depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');

// 🔌 Conexión
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();
if (!is_object($conn) || get_class($conn) !== 'mysqli') {
    echo "<div class='alert alert-danger'><i class='fa fa-exclamation-triangle'></i> Error de conexión.</div>";
    ob_end_flush();
    return;
}

// 🧩 Helpers
include_once dirname(__FILE__) . '/../helpers/EntidadesHelpers.php';
if (!function_exists('renderListadoEntidades')) {
    echo "<div class='alert alert-danger'>❌ Helper no disponible.</div>";
    ob_end_flush();
    return;
}
?>

<!-- ✅ Estilos -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="/assets/custom/styles.css">

<!-- ✅ Panel principal -->
<div class="panel panel-primary" style="margin-top: 20px;">
  <div class="panel-heading clearfix">
    <div class="pull-left">
      <h4 class="panel-title"><i class="fa fa-building"></i> Entidades registradas</h4>
    </div>
    <div class="pull-right">
      <div class="btn-group">
        <a href="/modulos/mantenimiento/mantenimiento.php" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Regresar</a>
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

<!-- ✅ Modales -->
<?php
function incluirModal($nombre) {
    $path = dirname(__FILE__) . "/../views/$nombre";
    if (is_file($path)) {
        include_once $path;
    } else {
        echo "<div class='alert alert-danger'>❌ Modal no encontrado: <code>$nombre</code></div>";
    }
}

incluirModal('ModalCrearEntidad.php');
incluirModal('ModalVerEntidad.php');
incluirModal('ModalEditarEntidad.php');
?>

<!-- ✅ Scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>



<script src="/modulos/mantenimiento/entidades/js/entidades.js"></script>

<?php ob_end_flush(); ?>