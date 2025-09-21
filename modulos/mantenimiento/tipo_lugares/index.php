<?php
	session_start();

	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	ini_set('error_log', __DIR__ . '/error_log.txt');

	require_once __DIR__ . '/../../../includes/config.php';
	$conn = getConnection();

	if (!$conn) {
		die("Error en la conexión: " . mysqli_connect_error());
		}
	if (empty($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    	header('Location: /login.php');
    	exit;
		}

	require_once 'controller.php';

	$tiposSeparados = listarTiposSeparados($conn);
	$tiposActivos   = $tiposSeparados['activos'];
	$tiposInactivos = $tiposSeparados['inactivos'];
?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h2 class="panel-title pull-left">
            <i class="fa fa-map-marker"></i> Mantenimiento: Tipo de Lugares
        </h2>
        <div class="pull-right">
            <a href="../mantenimiento.php" class="btn btn-default btn-sm">
                <i class="fa fa-arrow-left"></i> Volver
            </a>
            <button class="btn btn-success btn-sm" onclick="abrirFormulario()">
                <i class="fa fa-plus"></i> Agregar tipo
            </button>
        </div>
    </div>

	<ul class="nav nav-tabs">
  		<li class="active"><a href="#activos" data-toggle="tab">Activos</a></li>
  		<li><a href="#inactivos" data-toggle="tab">Inactivos</a></li>
	</ul>

	<div class="tab-content" style="margin-top: 15px;">
		<div class="tab-pane fade in active" id="activos">      
			<table class="table table-bordered table-hover table-condensed">
            	<thead>
                	<tr class="active">
                    	<th style="width: 60px;">ID</th>
                    	<th>Nombre</th>
                    	<th style="width: 160px;">Acciones</th>
                	</tr>
            	</thead>

            	<tbody>
                	<?php foreach ($tiposActivos as $tipo): ?>
                    	<tr data-id="<?= $tipo['id'] ?>">
                        	<td><?= $tipo['id'] ?></td>
                        	<td class="nombre"><?= ucfirst($tipo['nombre']) ?></td>
                        	<td>
                            	<button class="btn btn-warning btn-xs" onclick="editarTipo(<?= $tipo['id'] ?>)">
                                	<i class="fa fa-pencil"></i> Editar
                            	</button>
                            	<button class="btn btn-danger btn-xs" onclick="eliminarTipo(<?= $tipo['id'] ?>)">
                                	<i class="fa fa-trash"></i> Eliminar
                            	</button>
                        	</td>
                    	</tr>
                	<?php endforeach; ?>
            	</tbody>
        	</table>
    	</div>
		
		<div class="tab-pane fade" id="inactivos">
            <?php include 'panel_inactivos.php'; ?>
        </div>
	</div>
</div>

<div id="formulario" class="well" style="display:none"><?php include 'form.php'; ?></div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="script.js"></script>

