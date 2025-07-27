<?php
	// includes/conexion.php

	/**
 	* Retorna una instancia única de mysqli
 	*
 	* @return mysqli
 	*/

    	static $conn;

    	if ($conn instanceof mysqli) {
        	return $conn;
    		}

	// Configura tus datos de conexión aquí
	$host 			= 'localhost';
	$usuario 		= 'wi010232_ommz';
	$contrasena 	= 'Samantha2304';
	$base_de_datos 	= 'wi010232_sistema';

	// Crear conexión
	$conn = new mysqli($host, $usuario, $contrasena, $base_de_datos);

	// Verificar conexión
	if ($conn->connect_error) {
		die("Error de conexión: " . $conn->connect_error);
		}

	// Opcional: establecer juego de caracteres
	$conn->set_charset("utf8");
	
	return $conn;


?>

