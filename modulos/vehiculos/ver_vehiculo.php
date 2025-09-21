<?php
	// 1) Iniciar sesión si no está activa
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}

	// 2) Modo depuración (solo DEV)
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	ini_set('error_log', __DIR__ . '/error_log.txt');

	// 3) Cargar configuración y funciones
	require_once __DIR__ . '/../../includes/config.php';
	require_once __DIR__ . '/../../includes/funciones.php';

	// 4) Obtener conexión
	$conn = getConnection();

	// 5) Detectar si es petición AJAX
	$isAjax = isset($_GET['ajax']) && $_GET['ajax'] == '1';

	// 6) Validar ID recibido
	if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		if ($isAjax) {
        	echo "<div class='alert alert-danger'>ID inválido</div>";
        	exit;
    		} 
		else {
    		$_SESSION['error'] = "ID inválido";
        	header("Location: vehiculos.php");
        	exit;
    		}
		}
	$id = (int) $_GET['id'];

	// 7) Consultar datos del vehículo
	$sql = "
    	SELECT 
        	v.id, v.placa, v.modelo, v.anio, v.observaciones,
        	v.activo,
        	m.nombre AS marca,
        	t.nombre AS tipo,
        	e.razon_social AS empresa,
        	ev.nombre AS estado_operativo
    	FROM vehiculos v
    	JOIN marca_vehiculo   m  ON v.marca_id   = m.id
    	JOIN tipo_vehiculo    t  ON v.tipo_id    = t.id
    	JOIN empresa          e  ON v.empresa_id = e.id
    	JOIN estado_vehiculo  ev ON v.estado_id  = ev.id
    	WHERE v.id = ? 
    
		";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows === 0) {
    	if ($isAjax) {
        	echo "<div class='alert alert-warning'>Vehículo no encontrado</div>";
        	exit;
    		} 
		else {
        	$_SESSION['error'] = "Vehículo no encontrado";
        	header("Location: vehiculos.php");
        	exit;
    		}
		}

	$veh = $result->fetch_assoc();
	$fotos = [];
	$sqlFotos = "SELECT ruta_archivo, descripcion FROM vehiculo_fotos WHERE id_vehiculo = ?";
	$stmtFotos = $conn->prepare($sqlFotos);
	$stmtFotos->bind_param("i", $id);
	$stmtFotos->execute();
	$resFotos = $stmtFotos->get_result();
	if ($resFotos && $resFotos->num_rows > 0) {
    	$fotos = $resFotos->fetch_all(MYSQLI_ASSOC);
		}

	// Mostrar alerta si el vehículo está inactivo
	if (!$veh['activo']) {
    	echo "<div class='alert alert-warning'>Este vehículo está inactivo (eliminado lógicamente).</div>";
		}

	// 8) Registrar trazabilidad
	$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'visitante';
	registrarEnHistorial(
    	$conn,
    	$usuario,
    	'Consultó detalles del vehículo ID ' . $veh['id'],
    	'vehiculos',
    	obtenerIP()
		);

	// 9) Si es AJAX, devolver solo el contenido del modal
	if ($isAjax) {
?>
<h5 class="mb-3">Detalles del Vehículo</h5>
    <dl class="row">
        <dt class="col-sm-4">Placa</dt>
        <dd class="col-sm-8"><?= htmlspecialchars($veh['placa']) ?></dd>

        <dt class="col-sm-4">Marca</dt>
        <dd class="col-sm-8"><?= htmlspecialchars($veh['marca']) ?></dd>

        <dt class="col-sm-4">Tipo</dt>
        <dd class="col-sm-8"><?= htmlspecialchars($veh['tipo']) ?></dd>

        <dt class="col-sm-4">Modelo</dt>
        <dd class="col-sm-8"><?= htmlspecialchars($veh['modelo']) ?></dd>

        <dt class="col-sm-4">Año</dt>
        <dd class="col-sm-8"><?= htmlspecialchars($veh['anio']) ?></dd>

        <dt class="col-sm-4">Empresa</dt>
        <dd class="col-sm-8"><?= htmlspecialchars($veh['empresa']) ?></dd>

        <dt class="col-sm-4">Estado Operativo</dt>
        <dd class="col-sm-8"><?= htmlspecialchars($veh['estado_operativo']) ?></dd>

        <dt class="col-sm-4">Observaciones</dt>
        <dd class="col-sm-8"><?= nl2br(htmlspecialchars($veh['observaciones'])) ?></dd>
    </dl>
<?php
    exit;
}

// 10) Vista completa para carga directa
require_once '../../includes/header_erp.php';
?>

<div class="container mt-4">
    <h2 class="mb-4">Detalles del Vehículo</h2>
    <dl class="row">
        <dt class="col-sm-3">ID</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($veh['id']) ?></dd>

        <dt class="col-sm-3">Placa</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($veh['placa']) ?></dd>

        <dt class="col-sm-3">Marca</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($veh['marca']) ?></dd>

        <dt class="col-sm-3">Tipo</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($veh['tipo']) ?></dd>

        <dt class="col-sm-3">Modelo</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($veh['modelo']) ?></dd>

        <dt class="col-sm-3">Año</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($veh['anio']) ?></dd>

        <dt class="col-sm-3">Empresa</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($veh['empresa']) ?></dd>

        <dt class="col-sm-3">Estado Operativo</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($veh['estado_operativo']) ?></dd>

        <dt class="col-sm-3">Observaciones</dt>
        <dd class="col-sm-9"><?= nl2br(htmlspecialchars($veh['observaciones'])) ?></dd>
    </dl>

    <a href="vehiculos.php" class="btn btn-secondary">← Volver a listado</a>

	<?php if (!empty($fotos)): ?>
    	<h4 class="mt-4">Fotos del Vehículo</h4>
    	<div class="row">
    	<?php foreach ($fotos as $foto): ?>
            <div class="col-md-3 mb-3">
                <img src="<?= htmlspecialchars($foto['ruta_archivo']) ?>" class="img-fluid img-thumbnail" alt="<?= htmlspecialchars($foto['descripcion'] ?: 'Foto del vehículo') ?>">
                <?php if (!empty($foto['descripcion'])): ?>
                	<p class="text-center small text-muted"><?= htmlspecialchars($foto['descripcion']) ?></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    	</div>
	<?php else: ?>
    	<p class="text-muted">No hay fotos registradas para este vehículo.</p>
	<?php endif; ?>


    <a href="editar_vehiculo.php?id=<?= $veh['id'] ?>" class="btn btn-warning">✏️ Editar</a>
</div>

<?php require_once '../../includes/footer_erp.php'; ?>