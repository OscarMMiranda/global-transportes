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



	// 1. Validar método POST y parámetro
	if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    	$_SESSION['error'] = "Solicitud inválida.";
    	header("Location: index.php");
    	exit;
		}
	// 2. Convertir id a entero
	$id = (int) $_POST['id'];

	// 3. Preparar el UPDATE para marcar 'estado = 0'
	$stmt = $conn->prepare("
		UPDATE tipos_mercaderia 
    	SET estado = 0 
   		WHERE id = ?
		");

	// if (!$stmt) {
	// 	$_SESSION['error'] = "Error al preparar la baja: " . $conn->error;
	// 	} 
	// else {
    // 	$stmt->bind_param("i", $id);
    // 	if ($stmt->execute()) {
    //     	$_SESSION['msg'] = "eliminado";
    // 		} 
	// 	else {
    //     	$_SESSION['error'] = "Error al dar de baja: " . $stmt->error;
    // 		}
	// 	}

	// header("Location: index.php");
	// exit;


	if (!$stmt) {
    die("❌ Error prepare(): " . $conn->error);
}

// 4. Vincular parámetro y ejecutar
$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
    die("❌ Error execute(): " . $stmt->error);
}

// 5. Mensaje flash y redirección
$_SESSION['msg'] = "eliminado";
header("Location: index.php");
exit;

