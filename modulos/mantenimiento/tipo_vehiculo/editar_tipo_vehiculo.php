<?php
	// 1) Mostrar errores (solo en desarrollo)
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	// 2) Sesión y conexión
	session_start();
	require_once __DIR__ . '/../../../includes/conexion.php';

	// Verificar conexión antes de continuar
	if (!$conn || $conn->connect_error) {
    	die("Error de conexión a la base de datos: " . $conn->connect_error);
		}

	// 3) Control de acceso (solo admin)
	if (empty($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    	header('Location: ../../sistema/login.php');
    	exit;
		}

	// 4) Variables de mensajes
	$error = '';
	$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

	// 5) Soft-delete: marcar `fecha_borrado`
	if (isset($_GET['eliminar']) && ctype_digit($_GET['eliminar'])) {
    	$idDel = (int) $_GET['eliminar'];

    	$stmtDel = $conn->prepare(
        	"UPDATE tipo_vehiculo 
        	SET fecha_borrado = NOW() 
         	WHERE id = ? 
         	AND fecha_borrado IS NULL"
    		);
    	if (!$stmtDel) {
        	error_log("Error preparando consulta de eliminación: " . $conn->error);
        	die("Error al eliminar registro.");
    		}
    
    	$stmtDel->bind_param("i", $idDel);
    	if ($stmtDel->execute()) {
        	header("Location: editar_tipo_vehiculo.php?msg=eliminado");
        	exit;
    		} 
		else {
        	error_log("Error al ejecutar eliminación: " . $stmtDel->error);
        	$error = "Error al marcar borrado.";
    		}
    	$stmtDel->close();
		}

	// 6) Insertar nuevo tipo de vehículo
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {
    	$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    	$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';

    	if ($nombre === '') {
        	$error = "El nombre es obligatorio.";
    		}
		if (!$error) {
        	$stmtIns = $conn->prepare(
            	"INSERT INTO tipo_vehiculo 
             	(nombre, descripcion, fecha_creado)
             	VALUES (?, ?, NOW())"
        		);
        	if (!$stmtIns) {
            	error_log("Error preparando consulta de inserción: " . $conn->error);
            	die("Error al insertar registro.");
        		}
        	$stmtIns->bind_param("ss", $nombre, $descripcion);
        	if ($stmtIns->execute()) {
            	header("Location: editar_tipo_vehiculo.php?msg=agregado");
            	exit;
        		} 
			else {
            	error_log("Error al ejecutar inserción: " . $stmtIns->error);
            	$error = "Error al agregar.";
        		}
        	$stmtIns->close();
    		}
		}

	// 7) Editar un tipo existente
	$registro = null;
	if (isset($_GET['editar']) && ctype_digit($_GET['editar'])) 
		{
    	$idEdit = (int) $_GET['editar'];

    	// 7.1) Obtener datos del registro (solo si no está eliminado)
    	$stmt = $conn->prepare(
        	"SELECT id, nombre, descripcion 
         	FROM tipo_vehiculo
         	WHERE id = ?
         	AND fecha_borrado IS NULL"
    		);
    		if (!$stmt) {
        		error_log("Error preparando consulta de selección: " . $conn->error);
        		die("Error al obtener datos del registro.");
    			}

    	$stmt->bind_param("i", $idEdit);
    	$stmt->execute();
    	$res = $stmt->get_result();
    	if ($res->num_rows === 0) {
        	header("Location: editar_tipo_vehiculo.php");
        	exit;
    		}
    	$registro = $res->fetch_assoc();
    	$stmt->close();

    	// 7.2) Procesar actualización
    	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
        	$nombreNew = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
        	$descripcionNew = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
        	if ($nombreNew === '') {
            	$error = "El nombre es obligatorio.";
        		}
        	if (!$error) {
            	error_log("Actualizando ID: " . $idEdit);
            	error_log("Nuevo nombre: " . $nombreNew);
            	error_log("Nueva descripción: " . $descripcionNew);

            	$stmtUpd = $conn->prepare(
                	"UPDATE tipo_vehiculo
                	SET nombre = ?, descripcion = ?
                	WHERE id = ?"
            		);
            	if (!$stmtUpd) {
                	error_log("Error preparando consulta de actualización: " . $conn->error);
                	die("Error al actualizar registro.");
            		}

            	$stmtUpd->bind_param("ssi", $nombreNew, $descripcionNew, $idEdit);
            
            	if (!$stmtUpd->execute()) {
                	error_log("Error al ejecutar actualización: " . $stmtUpd->error);
                	die("Error al ejecutar actualización.");
            		}

            	if ($stmtUpd->affected_rows === 0) {
                	error_log("Actualización no modificó registros.");
                	$error = "No se realizó ninguna modificación.";
            		} 
				else {
                	header("Location: editar_tipo_vehiculo.php?msg=actualizado");
                	exit;
            		}

            	$stmtUpd->close();
        		}
    		}
		}

	// 8) Obtener lista de tipos de vehículos (no eliminados)
	if (!$conn || $conn->connect_error) {
    	die("Error de conexión antes de preparar consulta: " . $conn->connect_error);
		}

	$stmtList = $conn->prepare(
    	"SELECT id, nombre, descripcion, fecha_creado
    		FROM tipo_vehiculo
    		WHERE fecha_borrado IS NULL
    		ORDER BY nombre"
		);

	if (!$stmtList) {
    	error_log("Error preparando consulta de listado: " . $conn->error);
    	die("Error al obtener lista de registros.");
		}

	$stmtList->execute();
	$res = $stmtList->get_result();

	if (!$res) {
    	error_log("Error obteniendo resultados: " . $stmtList->error);
    	die("Error al obtener datos.");
		}

	$tipos = $res->fetch_all(MYSQLI_ASSOC);
	$stmtList->close();

	// 9) Cerrar conexión solo después de completar todo
	$conn->close();
?>




<!DOCTYPE html>
<html lang="es">
	<head>
  		<meta charset="UTF-8">
  		<meta name="viewport" content="width=device-width,initial-scale=1">
  		<title>Mantenimiento de Tipo de Vehículo – Global Transportes</title>
  		<link 
    		href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
    		rel="stylesheet"
  		>
  		<link rel="stylesheet" href="../css/base.css">
  		<link rel="stylesheet" href="../css/dashboard.css">
	</head>

	<body class="bg-light d-flex flex-column min-vh-100">

  		<!-- HEADER -->
  		<header class="dashboard-header bg-white shadow-sm py-3">
    		<div class="container d-flex justify-content-between align-items-center">
    			<h1 class="h4 mb-0">📦 Editar / Agregar Tipo de Vehículo</h1>
      			<a href="../../mantenimiento/mantenimiento.php" 
         			class="btn btn-outline-primary btn-sm">
        			<i class="fas fa-arrow-left me-1"></i> Volver al Mantenimiento
      			</a>
    		</div>
  		</header>



  <!-- MAIN -->
<main class="container flex-fill py-4">

    <!-- Mensajes de Estado -->
    <?php if (!empty($_GET['msg'])): ?>
      <div class="alert alert-success">
        <?php 
          switch($_GET['msg']) {
            case 'agregado':   echo 'Tipo de vehículo agregado.'; break;
            case 'actualizado':echo 'Registro actualizado.';     break;
            case 'eliminado':  echo 'Registro eliminado.';      break;
          }
        ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- FORMULARIO AGREGAR / EDITAR -->
    <form method="post" action="?editar=<?= isset($registro) ? $registro['id'] : '' ?>">
      <!-- Nombre -->
      <div class="row align-items-center mb-3">
        <label for="nombre" class="col-md-2 col-form-label text-md-end">Nombre</label>
        <div class="col-md-6">
          <input 
            type="text" 
            id="nombre" 
            name="nombre" 
            class="form-control" 
            value="<?= isset($registro) ? htmlspecialchars($registro['nombre']) : '' ?>"
            required
          >
        </div>
      </div>

      		<!-- Descripción -->
      		<div class="row align-items-start mb-3">
        		<label for="descripcion" class="col-md-2 col-form-label text-md-end">Descripción</label>
        		<div class="col-md-6">
          			<textarea 
            			id="descripcion" 
            			name="descripcion" 
            			class="form-control" 
            			rows="3"
            			required
          				><?= isset($registro) 
                			? htmlspecialchars($registro['descripcion']) 
                			: '' 
              		?></textarea>
        		</div>
      		</div>


      		<!-- Botones -->
      		<div class="row mb-5">
        		<div class="offset-md-2 col-md-6">
          			<button 
            			type="submit" 
            			name="<?= isset($registro) ? 'actualizar' : 'agregar' ?>" 
            			class="btn btn-<?= isset($registro) ? 'primary' : 'success' ?> me-2"
          			>
            			<?= isset($registro) ? 'Actualizar' : 'Agregar Tipo de Vehículo' ?>
          			</button>
          			<a href="editar_tipo_vehiculo.php" class="btn btn-outline-secondary">
            			Cancelar
          			</a>
        		</div>
      		</div>
    	</form>
    <hr>

    <!-- TABLA DE REGISTROS -->
		<!-- TABLA DE REGISTROS -->
	<div class="table-responsive">
  		<p>Registros: <?= count($tipos) ?></p>
  		<table class="table table-striped">
    		<thead>
      			<tr>
        			<th>ID</th>
        			<th>Nombre</th>
        			<th>Descripción</th>
        			<th>Acciones</th>
      			</tr>
    		</thead>
    		<tbody>
      			<?php if (empty($tipos)): ?>
        			<tr><td colspan="5" class="text-center">Sin registros</td></tr>
      			<?php else: ?>
        		<?php foreach ($tipos as $row): ?>
        		<tr>
          			<td><?= htmlspecialchars($row['id']) ?></td>
          			<td><?= htmlspecialchars($row['nombre']) ?></td>
          			<td><?= htmlspecialchars($row['descripcion']) ?></td>
          			<td>
            			<a href="?editar=<?= $row['id'] ?>" class="btn btn-sm btn-warning">✎</a>
            			<a href="?eliminar=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
               				onclick="return confirm('¿Eliminar este registro?')"
            			>🗑</a>
          			</td>
        		</tr>
        		<?php endforeach; ?>
      			<?php endif; ?>
    		</tbody>
  		</table>
	</div>
</main>

  <!-- FOOTER -->
  <footer class="mt-auto bg-white text-center py-3">
    <small class="text-muted">&copy; 2025 Global Transportes.</small>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
