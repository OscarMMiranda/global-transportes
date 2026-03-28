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


	<!-- HEADER DEL MÓDULO -->
	<?php include __DIR__ . '/componentes/header_tipo_destinos.php';?>

	<!-- TABS DEL MÓDULO -->
	<?php include __DIR__ . '/componentes/tabs_tipo_destinos.php'; ?>

	<!-- CONTENIDO DEL MÓDULO -->
	<?php include __DIR__ . '/componentes/tabs_contenido.php'; ?>

</div>

<?php include __DIR__ . '/componentes/modal_form.php'; ?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="script.js"></script>

