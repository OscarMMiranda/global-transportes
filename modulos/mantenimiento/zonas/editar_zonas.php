<?php


	session_start();

	// 1) Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors',     1);
ini_set('error_log',      __DIR__ . '/error_log.txt');

// 2) Cargar configuración y conexión
require_once __DIR__ . '/../../../includes/config.php';
// require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();




	require_once __DIR__ . '/controllers/zonas_controller.php';


	if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
  		header('Location: ../sistema/login.php'); exit;
		}

	// Acciones: eliminar / activar
	if (isset($_GET['eliminar'])) {
  		$id = (int) $_GET['eliminar'];
  		$stmt = $conn->prepare("UPDATE zonas SET estado = 0 WHERE id = ?");
  		$stmt->bind_param("i", $id);
  		$stmt->execute();
  		header("Location: editar_zonas.php?msg=Distrito desactivado"); exit;
		}

	if (isset($_GET['activar'])) {
  		$id = (int) $_GET['activar'];
  		$stmt = $conn->prepare("UPDATE zonas SET estado = 1 WHERE id = ?");
  		$stmt->bind_param("i", $id);
  		$stmt->execute();
  		header("Location: editar_zonas.php?msg=Distrito reactivado"); exit;
		}

	// Procesar formulario
	$error = '';
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  		$error = procesarDistrito($_POST);
  		if (!$error) {
    		header('Location: editar_zonas.php?msg=Guardado');
    		exit;
  			}
		}

	// Datos para vistas
	$zonasPadre = listarZonasPadre();         // zona(id, nombre)
	$distritos  = listarDistritosDisponibles(); // distritos(id, nombre)
	$subzonas   = listarDistritos();           // zonas con zona padre y distrito
	$registro = isset($_GET['id']) ? obtenerDistrito($_GET['id']) : ['id'=>0,'zona_id'=>0,'distrito_id'=>0];
?>


<!DOCTYPE html>
	<html lang="es">
	<head>
  		<meta charset="UTF-8">
  		<title>Distritos por Zona</title>
  		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body class="p-4">
  		<h1>Distritos por Zona</h1>

  		<a href="../mantenimiento.php" class="btn btn-outline-secondary mb-3">
    		← Volver
  		</a>

  		<button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalZona">
    		➕ Nuevo distrito por zona
  		</button>

  		<?php if ($error): ?>
    	<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  			<?php elseif (isset($_GET['msg'])): ?>
    		<div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
  				<?php endif; ?>

  				<table class="table table-striped table-bordered">
    				<thead>
      					<tr>
        					<th>ID</th>
        					<th>Zona</th>
        					<th>Distrito</th>
        					<th>Estado</th>
        					<th>Acciones</th>
      					</tr>
    				</thead>
    				<tbody>
      				<?php foreach ($subzonas as $z): ?>
        				<tr>
          					<td><?= $z['id'] ?></td>
          					<td><?= htmlspecialchars($z['zona_padre']) ?></td>
          					<td><?= htmlspecialchars($z['distrito']) ?></td>
          					<td><?= $z['estado'] == 1 ? 'Activo' : 'Inactivo' ?></td>
          					<td>
            					<a 
									href="editar_zonas.php?id=<?= $z['id'] ?>" 
									class="btn btn-sm btn-outline-primary">
									Editar
								</a>
            					<?php if ($z['estado'] == 1): ?>
              					<a 
									href="editar_zonas.php?eliminar=<?= $z['id'] ?>" 
									class="btn btn-sm btn-outline-danger"
                					onclick="return confirm('¿Desactivar distrito?')">
									Eliminar
								</a>
            					<?php else: ?>
              					<a 
									href="editar_zonas.php?activar=<?= $z['id'] ?>" 
									class="btn btn-sm btn-outline-success"
                					onclick="return confirm('¿Reactivar distrito?')">
									Activar
								</a>
            					<?php endif; ?>
          					</td>
        				</tr>
      				<?php endforeach; ?>
    				</tbody>
  				</table>

  <!-- Modal -->
  <div class="modal fade" id="modalZona" tabindex="-1">
    <div class="modal-dialog">
      <form method="post" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><?= $registro['id'] > 0 ? 'Editar Distrito-Zona' : 'Nuevo Distrito por Zona' ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" value="<?= $registro['id'] ?>">

          <div class="mb-3">
            <label>Zona principal</label>
            <select name="zona_id" class="form-select" required>
              <option value="">– Selecciona –</option>
              <?php foreach ($zonasPadre as $z): ?>
                <option value="<?= $z['id'] ?>" <?= $registro['zona_id'] == $z['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($z['nombre']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label>Distrito</label>
            <select name="distrito_id" class="form-select" required>
              <option value="">– Selecciona –</option>
              <?php foreach ($distritos as $d): ?>
                <option value="<?= $d['id'] ?>" <?= $registro['distrito_id'] == $d['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($d['nombre']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <?php if (isset($_GET['id']) && $registro['id'] > 0): ?>
  <script>
    new bootstrap.Modal(document.getElementById('modalZona')).show();
  </script>
  <?php endif; ?>
</body>
</html>

