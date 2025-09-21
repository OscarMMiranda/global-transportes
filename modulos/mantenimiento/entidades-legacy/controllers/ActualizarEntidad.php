<?php
	//	archivo	:	/modulos/mantenimiento/entidades/controllers/ActualizarEntidad.php

	// Fase 1: Entrada y conexión segura
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	ini_set('error_log', __DIR__ . '/error_log.txt');

	session_start();
	error_log("🚦 Entrando a ActualizarEntidad.php");
	error_log("📨 POST recibido: " . json_encode($_POST));

	// Cargar configuración
	$configPath = $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
	if (!file_exists($configPath)) {
    	error_log("❌ No se encontró config.php");
    	echo json_encode(['estado' => 'error', 'mensaje' => 'Config no encontrado']);
    	exit;
		}
	require_once $configPath;

	// Conexión
	$conn = getConnection();
	if (!($conn instanceof mysqli)) {
    	error_log("❌ Error de conexión");
    	echo json_encode(['estado' => 'error', 'mensaje' => 'Error de conexión']);
    	exit;
		}
	error_log("✅ Conexión establecida");

	// Fase 2: Validación de datos
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	if ($id <= 0) {
    	error_log("❌ ID inválido");
    	echo json_encode(['estado' => 'error', 'mensaje' => 'ID inválido']);
    	exit;
		}
	$datos = [
    	'nombre'         => isset($_POST['nombre']) ? trim($_POST['nombre']) : '',
    	'ruc'            => isset($_POST['ruc']) ? trim($_POST['ruc']) : '',
    	'direccion'      => isset($_POST['direccion']) ? trim($_POST['direccion']) : '',
    	'departamento_id'=> isset($_POST['departamento_id']) ? intval($_POST['departamento_id']) : 0,
    	'provincia_id'   => isset($_POST['provincia_id']) ? intval($_POST['provincia_id']) : 0,
    	'distrito_id'    => isset($_POST['distrito_id']) ? intval($_POST['distrito_id']) : 0,
    	'tipo_id'        => isset($_POST['tipo_id']) ? intval($_POST['tipo_id']) : 0,
		];
	error_log("📦 Datos normalizados: " . json_encode($datos));

	// Fase 3: Ejecución del modelo
	require_once $_SERVER['DOCUMENT_ROOT'] . '/modulos/mantenimiento/entidades/models/EntidadModel.php';
	if (!actualizarEntidad($conn, $id, $datos)) {
    	error_log("❌ Falló actualización");
    	echo json_encode(['estado' => 'error', 'mensaje' => 'Error al actualizar entidad']);
    	exit;
		}
	error_log("✅ Entidad actualizada");

	// Fase 4: Auditoría
	$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'sistema';
	$fecha   = date('Y-m-d H:i:s');
	$ip      = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
	$json    = json_encode($datos);

	$stmt = $conn->prepare("INSERT INTO auditoria_entidades (entidad_id, usuario, fecha, ip, datos_nuevos) VALUES (?, ?, ?, ?, ?)");
	if ($stmt) {
	    $stmt->bind_param("issss", $id, $usuario, $fecha, $ip, $json);
	    if ($stmt->execute()) {
	        error_log("📝 Auditoría registrada");
	    	} 
		else {
        	error_log("❌ Error al registrar auditoría: " . $stmt->error);
    		}
		} 
	else {
    	error_log("❌ Error preparando auditoría: " . $conn->error);
		}

	// Fase 5: Respuesta JSON para AJAX
	echo json_encode([
    	'estado'  => 'ok',
    	'mensaje' => 'Entidad actualizada correctamente',
    	'id'      => $id,
    	'usuario' => $usuario,
    	'fecha'   => $fecha
		]);
?>