<?php
	// 1) Arrancamos la sesión (si no se hizo en index.php)
	if (session_status() === PHP_SESSION_NONE) {
	    session_start();
		}

	// 2) Validamos que esté en sesión el ID y el nombre
	if (empty($_SESSION['reactivar_id']) || empty($_SESSION['reactivar_nombre'])) {
    	header('Location: index.php?error=' . urlencode('No hay nada para reactivar'));
    	exit;
		}

	$id     = (int) $_SESSION['reactivar_id'];
	$nombre = htmlspecialchars($_SESSION['reactivar_nombre'], ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
	<head>
  		<meta charset="UTF-8">
  		<title>Reactivar tipo eliminado</title>
  		<link
    		href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    		rel="stylesheet"
  		>
  		<link
			href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    		rel="stylesheet"
    		>
	</head>
	<body class="container py-5">
  		<h3 class="mb-4">
    		<i class="fas fa-undo text-warning"></i> Reactivar tipo de vehículo
  		</h3>
  		<div class="alert alert-warning">
    		Ya existe un registro eliminado con nombre
    		<strong class="text-danger"><?= $nombre ?></strong>.
  		</div>
  		<p>¿Deseas reactivarlo en lugar de crear uno nuevo?</p>

  		<form method="post" action="index.php?action=reactivar" class="d-flex gap-2">
    		<!-- Paso el ID por POST para mayor claridad -->
    		<input type="hidden" name="id" value="<?= $id ?>">
    		<button type="submit" class="btn btn-warning">
      			<i class="fas fa-redo"></i> Sí, reactivar
    		</button>
    		<a href="index.php" class="btn btn-secondary">
      			<i class="fas fa-times"></i> No, cancelar
    		</a>
  		</form>
	</body>
</html>
