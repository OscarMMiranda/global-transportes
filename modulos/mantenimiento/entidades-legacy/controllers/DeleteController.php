<?php
    //  archivo :   /modulos/mantenimiento/entidades/controllers/DeleteController.php

	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
	$conn = getConnection();

	// Validar ID recibido
	$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

	if ($id > 0 && ($conn instanceof mysqli)) {
    	// Marcar como inactivo (no eliminar físicamente)
    	$stmt = $conn->prepare("UPDATE entidades SET estado = 'inactivo', fecha_modificacion = NOW() WHERE id = ?");
    	if ($stmt) {
    	    $stmt->bind_param("i", $id);
    	    $stmt->execute();
    	    $stmt->close();

    	    // Log de auditoría (si aplica)
    	    error_log("🗑️ Entidad marcada como inactiva: ID $id");
    		} 
		else {
        	error_log("❌ Error al preparar DELETE para entidad ID $id");
    		}
		} 
	else {
    	error_log("⚠️ ID inválido o conexión fallida en DeleteController");
		}

	// Redirigir al listado
	header("Location: /modulos/mantenimiento/entidades/index.php?action=list");
	exit;