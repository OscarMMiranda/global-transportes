<?php
	// archivo: /modulos/orden_trabajo/controllers/TipoOTController.php

	// Inicia sesión si no está iniciada
	if (session_status() === PHP_SESSION_NONE) {
    	session_start();
		}

	// Conexión centralizada
	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';

	/**
	 * Devuelve un array de tipos de orden de trabajo
	 * Cada elemento: ['id' => ..., 'codigo' => ..., 'nombre' => ...]
	 */
	function obtenerTiposOT() {
    	global $conn;

    	if (!$conn) {
    	    error_log("❌ Conexión no disponible en TipoOTController");
        	return [];
    		}

    	$tiposOT = [];
    	$sql = "SELECT id, codigo, nombre FROM tipo_ot ORDER BY nombre ASC";
    	$stmt = $conn->prepare($sql);
    	if (!$stmt) {
        	error_log("❌ Error en prepare(): " . $conn->error);
        	return [];
    		}

    	if ($stmt->execute()) {
        	$resultado = $stmt->get_result();
        	while ($fila = $resultado->fetch_assoc()) {
            	$tiposOT[] = $fila;
        		}
        	$stmt->close();
    		} 
		else {
        	error_log("⚠️ Error al ejecutar consulta de tipos de OT: " . $stmt->error);
    		}

    	return $tiposOT;
}