<?php
	session_start();
	require_once '../../includes/conexion.php';

	// Validar llegada por POST
	if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id'])) {
    	$_SESSION['error'] = "Solicitud inválida.";
    	header("Location: vehiculos.php");
    	exit;
		}

	$id = (int) $_POST['id'];

	// Intentar restaurar el registro
	$stmt = $conn->prepare("UPDATE vehiculos SET activo = 1 WHERE id = ?");
	$stmt->bind_param("i", $id);

	if ($stmt->execute()) {
		$_SESSION['msg'] = "Vehículo reactivado correctamente.";
		} 
	else {
    	$_SESSION['error'] = "Error al reactivar: " . $stmt->error;
		}

	header("Location: vehiculos.php");
	exit;
