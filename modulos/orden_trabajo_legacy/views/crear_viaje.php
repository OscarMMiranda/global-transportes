<?php
	// archivo: /modulos/viaje/views/crear_viaje.php

	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';
	$conn     = getConnection();

	// 	01.	Validar número de OT
	$numeroOT = isset($_GET['numero_ot']) ? $_GET['numero_ot'] : '';
	if (!$numeroOT) {
    	die("❌ Número de OT no especificado.");
		}

	// Obtener orden_trabajo_id
	$sql    = "SELECT id FROM ordenes_trabajo WHERE numero_ot = ?";
	$stmt   = $conn->prepare($sql);
	$stmt->bind_param("s", $numeroOT);
	$stmt->execute();
	$res    = $stmt->get_result()->fetch_assoc();
	$ordenID = isset($res['id']) ? intval($res['id']) : 0;
	$stmt->close();

	if ($ordenID <= 0) {
    	die("❌ No se encontró la orden $numeroOT.");
		}

	// Fecha de hoy para precarga
	$hoy = date('Y-m-d');

	$sqlA = 
		"SELECT 
    		ac.id AS asignacion_id,
    		vt.placa AS placa_tracto,
    		vr.placa AS placa_remolque,
    		CONCAT(c.nombres, ' ', c.apellidos) AS nombre_conductor,
			ac.conductor_id,
    		ac.vehiculo_tracto_id,
    		ac.vehiculo_remolque_id
  		FROM asignaciones_conductor ac
  		JOIN vehiculos vt ON ac.vehiculo_tracto_id = vt.id
  		JOIN vehiculos vr ON ac.vehiculo_remolque_id = vr.id
		JOIN conductores c ON ac.conductor_id = c.id
  		WHERE ac.estado_id = 1
    	AND ac.fecha_inicio <= CURDATE()
    	AND (ac.fecha_fin IS NULL OR ac.fecha_fin >= CURDATE())
  		ORDER BY ac.fecha_inicio DESC
		";

	$stmtA = $conn->prepare($sqlA);
	if (!$stmtA) {
    	die("❌ Error en prepare(): " . $conn->error);
		}
	$stmtA->execute();
	$resultA = $stmtA->get_result();
	$asignaciones = [];
	while ($row = $resultA->fetch_assoc()) {
    	$asignaciones[] = $row;
		}
	$stmtA->close();
?>

<!DOCTYPE html>
<html lang="es">
	<head>
  		<meta charset="UTF-8">
  		<title>Registrar Viaje (OT <?= htmlspecialchars($numeroOT) ?>)</title>
  		<link 
    		href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
    		rel="stylesheet"/>
  		<link 
    		href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" 
    		rel="stylesheet"/>
  		<style>
    		body { background-color: #f4f6f8; }
    		.form-section {
      			max-width: 700px;
      			margin: 3rem auto;
      			padding: 2rem;
      			background: #fff;
      			border-radius: 8px;
      			box-shadow: 0 0 10px rgba(0,0,0,0.1);
    			}
    		.form-section h2 { margin-bottom: 1.5rem; }
    		label { font-weight: 600; }
  		</style>
	</head>
<body>

<div class="form-section">
  <h2 class="text-center text-primary">
    Registrar Viaje (OT <?= htmlspecialchars($numeroOT) ?>)
  </h2>

  <form 
    id="viajeForm" 
    class="needs-validation" 
    novalidate
    action="/modulos/viaje/controllers/GuardarViajeController.php" 
    method="POST"
  >
    <input type="hidden" name="orden_trabajo_id" value="<?= $ordenID ?>">

    <div class="row">

      <!-- Fecha de salida -->
      <div class="col-md-6 mb-3">
        <label for="fecha_salida">
          Fecha de salida
          <i class="bi bi-info-circle text-info"
             data-bs-toggle="tooltip"
             title="Selecciona la fecha de inicio del viaje.">
          </i>
        </label>
        <input
          type="date"
          id="fecha_salida"
          name="fecha_salida"
          class="form-control"
          required
          value="<?= $hoy ?>"
        >
      </div>

      <!-- Fecha de llegada -->
      <div class="col-md-6 mb-3">
        <label for="fecha_llegada">
          Fecha de llegada
          <i class="bi bi-info-circle text-info"
             data-bs-toggle="tooltip"
             title="Debe ser igual o posterior a la fecha de salida.">
          </i>
        </label>
        <input
          type="date"
          id="fecha_llegada"
          name="fecha_llegada"
          class="form-control"
          required
          value="<?= $hoy ?>"
        >
        <div class="invalid-feedback">
          La fecha de llegada no puede ser anterior a la fecha de salida.
        </div>
      </div>
    </div>

	<!-- 2. Selección de vehículo (tracto) -->
  	<div class="mb-3">
    	<label for="vehiculo_tracto_id">Vehículo (Tracto)</label>
    	<select name="vehiculo_tracto_id" id="vehiculo_tracto_id" class="form-select" required>
      		<option value="">-- Selecciona tracto --</option>
      		<?php foreach ($asignaciones as $a): ?>
        		<option 
          			value="<?= $a['vehiculo_tracto_id'] ?>"
          			data-remolque-id="<?= $a['vehiculo_remolque_id'] ?>"
          			data-remolque-placa="<?= $a['placa_remolque'] ?>"
          			data-conductor-id="<?= $a['conductor_id'] ?>"
					data-conductor-nombre="<?= htmlspecialchars($a['nombre_conductor']) ?>"
        		>
          			<?= htmlspecialchars($a['placa_tracto']) ?>
        	</option>
      		<?php endforeach; ?>
    	</select>
  	</div>

	<!-- 3. Remolque asignado -->
  	<div class="mb-3">
    	<label>Remolque asignado</label>
    	<input type="text" id="placa_remolque" class="form-control" readonly placeholder="Se completa automáticamente">
    	<input type="hidden" name="vehiculo_remolque_id" id="vehiculo_remolque_id">
  	</div>

  	<!-- 4. Conductor asignado -->
  	<div class="mb-3">
    	<label>Conductor asignado</label>
    	<input type="text" id="nombre_conductor" class="form-control" readonly placeholder="Se completa automáticamente">
    	<input type="hidden" name="conductor_id" id="conductor_id">
  	</div>

    <div class="mb-3">
      <label for="destino">Destino</label>
      <input
        type="text"
        id="destino"
        name="destino"
        class="form-control"
        required
      >
    </div>

    <div class="mb-3">
      <label for="distancia_km">Distancia (km)</label>
      <input
        type="number"
        id="distancia_km"
        name="distancia_km"
        class="form-control"
        required
      >
    </div>

    <div class="mb-3">
      <label for="chofer">Chofer</label>
      <input
        type="text"
        id="chofer"
        name="chofer"
        class="form-control"
        required
      >
    </div>

    <div class="mb-3">
      <label for="estado">Estado</label>
      <select name="estado" id="estado" class="form-select">
        <option value="Programado">Programado</option>
        <option value="En ruta">En ruta</option>
        <option value="Finalizado">Finalizado</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="observaciones">Observaciones</label>
      <textarea
        id="observaciones"
        name="observaciones"
        class="form-control"
        rows="3"
      ></textarea>
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-success px-4">
        Registrar viaje
      </button>
      <a 
        href="/modulos/orden_trabajo/views/ver.php?orden_trabajo_id=<?= $ordenID ?>"
        class="btn btn-secondary ms-2"
      >
        ← Volver a la orden
      </a>
    </div>
  </form>
</div>

<script 
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
</script>
<script>
  // Inicializar tooltips de Bootstrap
  document.querySelectorAll('[data-bs-toggle="tooltip"]')
    .forEach(el => new bootstrap.Tooltip(el));

  // Validación inline de fechas
  (function(){
    var form    = document.getElementById('viajeForm');
    var salida  = document.getElementById('fecha_salida');
    var llegada = document.getElementById('fecha_llegada');

    function validarFechas() {
      if (llegada.value < salida.value) {
        llegada.classList.add('is-invalid');
        return false;
      } else {
        llegada.classList.remove('is-invalid');
        return true;
      }
    }

    salida.addEventListener('change', validarFechas);
    llegada.addEventListener('change', validarFechas);

    form.addEventListener('submit', function(e){
      if (!validarFechas()) {
        e.preventDefault();
        llegada.focus();
      }
    });
  })();

	// Actualizar remolque y conductor al elegir tracto
  	document.getElementById('vehiculo_tracto_id').addEventListener('change', function() {
    const selected = this.selectedOptions[0];
    const remId    = selected.dataset.remolqueId;
    const remPlaca = selected.dataset.remolquePlaca;
    const condId   = selected.dataset.conductorId
	const condNombre = selected.dataset.conductorNombre;

    document.getElementById('vehiculo_remolque_id').value = remId;
    document.getElementById('placa_remolque').value       = remPlaca;
    document.getElementById('conductor_id').value         = condId;
	document.getElementById('nombre_conductor').value     = condNombre;

    // Si tienes un array JS con nombres de conductores, puedes mostrarlo aquí
    // document.getElementById('nombre_conductor').value = conductorNombres[condId];
  });


</script>
</body>
</html>