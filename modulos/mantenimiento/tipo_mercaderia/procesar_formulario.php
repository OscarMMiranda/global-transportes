<?php
	// procesar_formulario.php
	session_start();
	// require_once '../../../includes/conexion.php';

	require_once __DIR__ . '/../../../includes/conexion.php';

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    	$nombre = trim($_POST['nombre']);
    	$descripcion = trim($_POST['descripcion']);
    	$id = isset($_POST['id']) ? (int)$_POST['id'] : null;

    	if (empty($nombre)) {
        	$_SESSION['error'] = "El nombre es obligatorio.";
        	header("Location: " . ($id ? "editar.php?id=$id" : "index.php"));
        	exit;
    		}

    	if ($id) {
        	// Actualizar
        	$stmt = $conn->prepare("UPDATE tipos_mercaderia SET nombre = ?, descripcion = ? WHERE id = ?");
        	$stmt->bind_param("ssi", $nombre, $descripcion, $id);
        	$stmt->execute();
        	$_SESSION['msg'] = "actualizado";
    		} 
		else {
        	// Insertar
        	$stmt = $conn->prepare("INSERT INTO tipos_mercaderia (nombre, descripcion) VALUES (?, ?)");
        	$stmt->bind_param("ss", $nombre, $descripcion);
        	$stmt->execute();
        	$_SESSION['msg'] = "agregado";
    		}

    	header("Location: index.php");
    	exit;
		}
?>
