<?php
	session_start();
	
	// 2) Modo depuración (solo DEV)
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors',     1);
    ini_set('error_log',      __DIR__ . '/error_log.txt');

    // 3) Cargar config.php (define getConnection() y rutas)
    require_once __DIR__ . '/../../includes/config.php';

    // 4) Obtener la conexión
    $conn = getConnection();

	// VERIFICAR QUE EL USUARIO SEA ADMINISTRADOR
	if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') 
		{
    	header("Location: /login.php");
    	exit();
		}

	// VERIFICAR CONEXIÓN CON LA BASE DE DATOS
	if (!$conn) 
		{
    	die("Error de conexión con la base de datos: " . mysqli_connect_error());
		}

	// OBTENER EL ESTADO "ACTIVO"
	$sql_estado_activo = "SELECT id FROM estado_asignacion WHERE nombre = 'activo'";
	$result_estado_activo = $conn->query($sql_estado_activo);
	if (!$result_estado_activo) 
		{
    	die("Error en la consulta SQL de estado activo: " . $conn->error);
		}
	if ($result_estado_activo->num_rows === 0) 
		{
    	die("No hay registros con nombre 'activo' en estado_asignacion.");
		}

	$row_estado_activo = $result_estado_activo->fetch_assoc();
	$estado_id_activo = $row_estado_activo['id'];

	// OBTENER EL ESTADO "FINALIZADO"
	$sql_estado_finalizado = "SELECT id FROM estado_asignacion WHERE nombre = 'finalizado'";
	$result_estado_finalizado = $conn->query($sql_estado_finalizado);
	if (!$result_estado_finalizado) 
		{
    	die("Error en la consulta SQL de estado finalizado: " . $conn->error);
		}
	if ($result_estado_finalizado->num_rows === 0) 
		{
    	die("No hay registros con nombre 'finalizado' en estado_asignacion.");
		}
	$row_estado_finalizado = $result_estado_finalizado->fetch_assoc();
	$estado_id_finalizado = $row_estado_finalizado['id'];

	// 1.1) Asignaciones Activas
$sql_activos = "
  SELECT 
    ac.id,
    vt.placa   AS tracto_placa,
    vr.placa   AS remolque_placa,
    vt.modelo  AS tracto_modelo,
    vr.modelo  AS remolque_modelo,
    CONCAT(c.nombres, ' ', c.apellidos) AS conductor,
    ac.fecha_inicio,
    es.nombre   AS estado
  FROM asignaciones_conductor ac
  JOIN vehiculos vt         ON ac.vehiculo_tracto_id   = vt.id
  JOIN vehiculos vr         ON ac.vehiculo_remolque_id = vr.id
  JOIN conductores c        ON ac.conductor_id         = c.id
  JOIN estado_asignacion es ON ac.estado_id            = es.id
  WHERE ac.estado_id = ?
  ORDER BY ac.fecha_inicio DESC
";
$stmt_activos = $conn->prepare($sql_activos);
if (! $stmt_activos) {
    die("Error al preparar consulta de asignaciones activas: " . $conn->error);
}
$stmt_activos->bind_param("i", $estado_id_activo);
$stmt_activos->execute();
$result_activos = $stmt_activos->get_result();


// 1.2) Historial de Asignaciones
$sql_historial = "
  SELECT 
    ac.id,
    vt.placa   AS tracto_placa,
    vr.placa   AS remolque_placa,
    vt.modelo  AS tracto_modelo,
    vr.modelo  AS remolque_modelo,
    CONCAT(c.nombres, ' ', c.apellidos) AS conductor,
    ac.fecha_inicio,
    COALESCE(ac.fecha_fin, 'En uso') AS fecha_fin,
    es.nombre   AS estado
  FROM asignaciones_conductor ac
  JOIN vehiculos vt         ON ac.vehiculo_tracto_id   = vt.id
  JOIN vehiculos vr         ON ac.vehiculo_remolque_id = vr.id
  JOIN conductores c        ON ac.conductor_id         = c.id
  JOIN estado_asignacion es ON ac.estado_id            = es.id
  WHERE ac.estado_id = ?
  ORDER BY ac.fecha_fin DESC
";
$stmt_historial = $conn->prepare($sql_historial);
if (! $stmt_historial) {
    die("Error al preparar consulta de historial: " . $conn->error);
}
$stmt_historial->bind_param("i", $estado_id_finalizado);
$stmt_historial->execute();
$result_historial = $stmt_historial->get_result();



	if ($result_historial === false) 
		{
    	die("Error en la consulta SQL de historial de asignaciones: " . $conn->error);
		}
?>

<!DOCTYPE html>
	<html lang="es">
	<head>
    	<meta charset="UTF-8">
    	<title>Asignaciones de Conductores</title>
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    	<!-- Bootstrap CSS -->
    	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    	<!-- DataTables CSS (opcional, para mejor paginación y búsquedas) -->
    	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    	<!-- CSS Personalizado -->
    	<link rel="stylesheet" href="../../css/asignaciones.css">
    	<!-- FontAwesome para íconos -->
    	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	</head>
	<body>
		<div class="container my-5">
    		<h1 class="mb-4">Asignaciones de Conductores</h1>
    		<div class="mb-3">
        		<a href="asignar_conductor.php" class="btn btn-primary me-2">
            		<i class="fas fa-plus"></i> Asignar Vehículo
        	</a>
        	<a href="../erp_dashboard.php" class="btn btn-secondary">
            	<i class="fas fa-arrow-left"></i> Volver al Dashboard
        	</a>
    	</div>

    	<!-- Asignaciones Activas -->
    	<h2 class="mt-4">🚗 Asignaciones Activas</h2>
    	<?php if ($result_activos->num_rows > 0) { ?>
        	<div class="table-responsive">
            	<table id="tablaActivas" class="table table-bordered table-hover">
                	<thead class="table-primary">
                    	<tr>
                        	<th>Vehículo</th>
                        	<th>Modelo</th>
                        	<th>Conductor</th>
                        	<th>Fecha Asignación</th>
                        	<th>Estado</th>
                        	<th>Acciones</th>
                    	</tr>
                	</thead>
                	<tbody>
  <?php while ($a = $result_activos->fetch_assoc()): ?>
    <tr>
      <td>
        <?= htmlspecialchars($a['tracto_placa'] . ' / ' . $a['remolque_placa']) ?>
      </td>
      <td>
        <?= htmlspecialchars($a['tracto_modelo'] . ' / ' . $a['remolque_modelo']) ?>
      </td>
      <td><?= htmlspecialchars($a['conductor']) ?></td>
      <td><?= htmlspecialchars($a['fecha_inicio']) ?></td>
      <td><?= htmlspecialchars($a['estado']) ?></td>
      <td>
        <button class="btn btn-danger btn-sm btn-finalizar"
                data-finalizar-id="<?= $a['id'] ?>">
          <i class="fas fa-times-circle"></i> Finalizar
        </button>
      </td>
    </tr>
  <?php endwhile; ?>
</tbody>
            	</table>
        	</div>
    	<?php } else { ?>
        	<p>No hay asignaciones activas.</p>
    	<?php } ?>

    	<!-- Historial de Asignaciones -->
    	<h2 class="mt-5">📜 Historial de Asignaciones</h2>
    	<?php if ($result_historial->num_rows > 0) { ?>
        	<div class="table-responsive">
            	<table id="tablaHistorial" class="table table-bordered table-hover">
                	<thead class="table-secondary">
                    	<tr>
                        	<th>Vehículo</th>
                        	<th>Modelo</th>
                        	<th>Conductor</th>
                        	<th>Fecha Asignación</th>
                        	<th>Fecha Fin</th>
                        	<th>Estado</th>
                    	</tr>
                	</thead>
                	<tbody>
                    	<?php while ($historial = $result_historial->fetch_assoc()) { ?>
                        	<tr>
                            	<td><?= htmlspecialchars($historial['placa']) ?></td>
                            	<td><?= htmlspecialchars($historial['modelo']) ?></td>
                            	<td><?= htmlspecialchars($historial['nombres'] . ' ' . $historial['apellidos']) ?></td>
                            	<td><?= htmlspecialchars($historial['fecha_inicio']) ?></td>
                            	<td><?= htmlspecialchars($historial['fecha_fin']) ?></td>
                            	<td><?= htmlspecialchars($historial['estado']) ?></td>
                        	</tr>
                    	<?php } ?>
                	</tbody>
            	</table>
        	</div>
    	<?php } else { ?>
        	<p>No hay historial de asignaciones.</p>
    	<?php } ?>
	</div>

	<!-- Modal de Confirmación para Finalizar Asignación -->
	<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  		<div class="modal-dialog modal-dialog-centered">
    		<div class="modal-content">
      			<div class="modal-header">
        			<h5 class="modal-title" id="confirmModalLabel">Confirmar Finalización</h5>
        			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
</div>
<div class="modal-body">
¿Estás seguro de que deseas finalizar esta asignación?
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
<button type="button" class="btn btn-danger" id="confirmBtn">Confirmar</button>
</div>
</div>
</div>
	</div>

	<!-- jQuery (necesario para DataTables y el manejo del modal) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<!-- DataTables JS (opcional) -->
	<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
	<!-- Bootstrap JS Bundle (incluye Popper) -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<script>
	$(document).ready(function()
		{
		// Inicializar DataTables (opcional)
    	$('#tablaActivas').DataTable(
			{
"language": 
				{
"url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        		}
    		});
    	$('#tablaHistorial').DataTable({
        "language": {
"url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        }
    });
    
    // Modal de confirmación para finalizar asignación
    var asignacionId;
    $('.btn-finalizar').on('click', function(e){
        e.preventDefault();
        asignacionId = $(this).data('finalizar-id');
        var confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
        confirmModal.show();
    });

    $('#confirmBtn').on('click', function(){
        // Redirigir a finalizar_asignacion.php con el ID de asignación
        window.location.href = "finalizar_asignacion.php?id=" + asignacionId;
    });
});
</script>
</body>
</html>
