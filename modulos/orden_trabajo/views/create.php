<?php
	// archivo: /modulos/orden_trabajo/views/create.php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	ini_set('error_log', __DIR__ . '/create_error.log');

	// 🔧 Conexión
	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';
	$conn = getConnection();
	if (!isset($conn) || !$conn instanceof mysqli) {
		die("❌ Error de conexión.");
		}

	// 🔧 Carga de clientes activos
	require_once $_SERVER['DOCUMENT_ROOT'] . '/modulos/orden_trabajo/controllers/ClienteController.php';
	$clientes = obtenerClientesActivos();

	// 🔧 Carga de empresas activas
	require_once $_SERVER['DOCUMENT_ROOT'] . '/modulos/orden_trabajo/controllers/EmpresaController.php';
	$empresas = obtenerEmpresasActivas(); // Asegurate de que la función se llame así
	

	require_once $_SERVER['DOCUMENT_ROOT'] . '/modulos/orden_trabajo/controllers/TipoOTController.php';
	$tiposOT  = obtenerTiposOT();


	// 🔧 Generación de correlativo

	$anioActual = date('Y');
	$sqlUltima = "SELECT numero_ot FROM ordenes_trabajo ORDER BY id DESC LIMIT 1";
	$resultUltima = $conn->query($sqlUltima);

	$ultimoOT = '';
	if ($resultUltima && $resultUltima->num_rows > 0) {
    	$fila = $resultUltima->fetch_assoc();
    	$ultimoOT = $fila['numero_ot'];
		}


	$partes = explode('-', $ultimoOT);
	$correlativo = isset($partes[0]) ? intval($partes[0]) + 1 : 1;
	$anio = date('Y');
	$nuevoOT = $correlativo . '-' . $anio;


	require_once __DIR__ . '/../../../includes/header_erp.php';

	$pageTitle = '➕ Crear Nueva Orden de Trabajo';

	include __DIR__ . '/partials/head.php';

?>

<body>
	<div class="container mt-4">
		<h2 class="text-center text-success mb-4">
			<?php echo $pageTitle; ?>
		</h2>

  		<?php if (isset($_GET['error'])): ?>
    		<div class="alert alert-danger text-center">
				<?= htmlspecialchars($_GET['error']) ?>
			</div>
  		<?php endif; ?>

  		<form action="../controllers/CreateController.php" method="POST" class="border p-4 shadow-sm bg-light">
    		<div class="row mb-3">
      			<div class="col-md-4">
        			<label for="numero_correlativo" class="form-label">Número Correlativo</label>
        			<input type="text" name="numero_correlativo" id="numero_correlativo" class="form-control" value="<?= htmlspecialchars($nuevoOT) ?>" readonly>
      			</div>
      			<div class="col-md-4">
        			<label for="anio_ot" class="form-label">Año OT</label>
        			<input type="text" name="anio_ot" id="anio_ot" class="form-control" value="<?= date('Y') ?>" required>
      			</div>
      			<div class="col-md-4">
        			<label for="fecha" class="form-label">Fecha</label>
        			<input type="date" name="fecha" id="fecha" class="form-control" required>
      			</div>
    		</div>
    		<div class="row mb-3">
      			<div class="col-md-4">
        			<label for="cliente_id" class="form-label">Cliente</label>
        			<select name="cliente_id" id="cliente_id" class="form-select" required>
          				<option value="">Seleccione...</option>
          				<?php foreach ($clientes as $cliente): ?>
            				<option value="<?= htmlspecialchars($cliente['id']) ?>">
              					<?= htmlspecialchars($cliente['nombre']) ?>
            				</option>
          				<?php endforeach; ?>
        			</select>
      			</div>
      			<div class="col-md-4">
        			<label for="tipo_ot_id" class="form-label">Tipo OT</label>
        			<select name="tipo_ot_id" id="tipo_ot_id" class="form-select" required>
          				<option value="">Seleccione...</option>
            <?php foreach ($tiposOT as $t): ?>
              <option value="<?= htmlspecialchars($t['id']) ?>">
                <?= htmlspecialchars("{$t['codigo']} - {$t['nombre']}") ?>
              </option>
            <?php endforeach; ?>
        			</select>
      			</div>
      <div class="col-md-4">
        <label for="empresa_id" class="form-label">Empresa</label>
        <select name="empresa_id" id="empresa_id" class="form-select" required>
  <option value="">Seleccione...</option>
  <?php foreach ($empresas as $empresa): ?>
    <option value="<?= htmlspecialchars($empresa['id']) ?>">
      <?= htmlspecialchars($empresa['razon_social']) ?>
    </option>
  <?php endforeach; ?>
</select>
      </div>
    </div>

    <div class="mb-3">
      <label for="oc_cliente" class="form-label">Orden de Cliente (OC)</label>
      <input type="text" name="oc_cliente" id="oc_cliente" class="form-control" required>
    </div>

    <!-- Campos dinámicos según tipo OT -->
    <div id="campo_dam" class="mb-3 d-none">
      <label for="numero_dam" class="form-label">Número DAM</label>
      <input type="text" name="numero_dam" id="numero_dam" class="form-control">
    </div>

    	<div id="campo_booking" class="mb-3 d-none">
      		<label for="numero_booking" class="form-label">Número Booking</label>
      		<input type="text" name="numero_booking" id="numero_booking" class="form-control">
    	</div>

    	<div id="campo_otros" class="mb-3 d-none">
    		<label for="otros" class="form-label">Otros</label>
    		<input type="text" name="otros" id="otros" class="form-control">
    	</div>

		<div class="text-center mt-4">
    		<button type="submit" class="btn btn-success btn-lg">💾 Guardar Orden</button>
    		<a href="/modulos/orden_trabajo/index.php" class="btn btn-secondary btn-lg">
			↩️ Volver al Listado
			</a>
		</div>
	</form>
</div>

<?php include __DIR__ . '/partials/scripts.php'; ?>

	<script>
  		// Mostrar campos dinámicos según tipo OT
  		document.getElementById('tipo_ot_id').addEventListener('change', function() {
    		const tipo = parseInt(this.value);
    		document.getElementById('campo_dam').classList.toggle('d-none', tipo !== 2);
    		document.getElementById('campo_booking').classList.toggle('d-none', tipo !== 3);
			document.getElementById('campo_otros').classList.toggle('d-none', tipo !== 1);
  			});
	</script>
</body>