<?php
	// archivo: includes/conexion.php
	$host = 'localhost';
	$usuario = 'wi010232_ommz';
	$contrasena = 'Samantha2304';
	$base_de_datos = 'wi010232_sistema';

	$conn = new mysqli($host, $usuario, $contrasena, $base_de_datos);

	if ($conn->connect_error) {
		error_log("Error de conexión: " . $conn->connect_error);
		die("Error al conectar a la base de datos.");
	}

	$conn->set_charset("utf8");
?>
