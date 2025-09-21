<?php
	// archivo	:	/includes/conexion.php

	/**
	 * Devuelve una única instancia de mysqli
	 * @return mysqli
	 */
	function getConnection() 
		{
    	static $conn;

    	if ($conn instanceof mysqli) {
			return $conn;
    		}

		$host       = 'localhost';
		$usuario    = 'wi010232_ommz';
		$contrasena = 'Samantha2304';
		$base       = 'wi010232_sistema';

		$conn = new mysqli($host, $usuario, $contrasena, $base);
		
		if ($conn->connect_error) {
    		error_log("❌ Error de conexión: " . $conn->connect_error);
    		return false;
			}

	
		// Charset UTF-8
		if (!$conn->set_charset("utf8")) {
        	error_log("⚠️ Error al establecer charset UTF-8: " . $conn->error);
    		}

		error_log("✅ Conexión establecida correctamente");

		return $conn;
		}
