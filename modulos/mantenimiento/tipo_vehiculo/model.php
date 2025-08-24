<?php
	// model.php – Módulo Tipo de Vehículo

	/**
	 * Obtiene todos los tipos de vehículo no eliminados.
	 * @return array
	 */
	function listarTipos($conn) {
    	$sql = 
			"SELECT 
				id, 
				nombre, 
				descripcion, 
				fecha_creado,
				fecha_actualizacion
			FROM tipo_vehiculo
			WHERE fecha_borrado IS NULL
			ORDER BY nombre";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$res = $stmt->get_result();
		$tipos = $res->fetch_all(MYSQLI_ASSOC);
		$stmt->close();
		return $tipos;
		}

	/**
	 * Recupera un registro por ID si no está borrado.
	 * @return array|null
	 */
	function getTipoById($conn, $id) {
		$sql = "SELECT id, nombre, descripcion
			FROM tipo_vehiculo
			WHERE id = ? AND fecha_borrado IS NULL";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$res = $stmt->get_result();
		$tipo = $res->num_rows ? $res->fetch_assoc() : null;
		$stmt->close();
		return $tipo;
		}

	/**
	 * Verifica que el nombre no esté duplicado.
	 * @return bool
	 */
	function validarUnicidadNombre($conn, $nombre, $idActual = null) {
	    $sql = "SELECT COUNT(*) FROM tipo_vehiculo
	            WHERE nombre = ? AND fecha_borrado IS NULL";
	    if ($idActual !== null) {
	        $sql .= " AND id != ?";
	    }
	    $stmt = $conn->prepare($sql);
	    if ($idActual !== null) {
	        $stmt->bind_param("si", $nombre, $idActual);
	    } else {
	        $stmt->bind_param("s", $nombre);
	    }
	    $stmt->execute();
	    $stmt->bind_result($count);
    	$stmt->fetch();
    	$stmt->close();
    	return $count === 0;
		}

	/**
	 * Inserta un nuevo tipo de vehículo y registra el historial.
	 * @return bool
	 */
	function insertTipo($conn, $nombre, $descripcion, $usuarioId) {
	    if (!validarUnicidadNombre($conn, $nombre)) {
	        return false;
	    	}
	    $sql = "INSERT INTO tipo_vehiculo
	            (nombre, descripcion, fecha_creado)
	            VALUES (?, ?, NOW())";
	    $stmt = $conn->prepare($sql);
	    $stmt->bind_param("ss", $nombre, $descripcion);
	    $ok = $stmt->execute();
	    if ($ok) {
	        $insertId = $stmt->insert_id;
	        $cambio = "Creado con nombre='$nombre', descripcion='$descripcion'";
	        registrarHistorialTipo($conn, $insertId, $usuarioId, $cambio);
	    	}
	    $stmt->close();
	    return $ok;
		}

	/**
	 * Actualiza un tipo de vehículo y registra historial de cambios.
	 * @return bool
	 */
	function updateTipo($conn, $id, $nombreNew, $descripcionNew, $usuarioId) {
	    if (!validarUnicidadNombre($conn, $nombreNew, $id)) {
	        return false;
	    	}
	    $old = getTipoById($conn, $id);
	    if (!$old) {
	        return false;
	    	}
	    $sql = "UPDATE tipo_vehiculo
	            SET nombre = ?, descripcion = ?, fecha_actualizacion = NOW()
	            WHERE id = ?";
	    $stmt = $conn->prepare($sql);
	    $stmt->bind_param("ssi", $nombreNew, $descripcionNew, $id);
	    $ok = $stmt->execute();
	    if ($ok && $stmt->affected_rows > 0) {
	        $cambio = sprintf(
	            "Nombre: '%s'→'%s'; Descripcion: '%s'→'%s'",
	            $old['nombre'], $nombreNew,
	            $old['descripcion'], $descripcionNew
	        );
	        registrarHistorialTipo($conn, $id, $usuarioId, $cambio);
		    }
    	$stmt->close();
	    return $ok;
		}

	/**
	 * Soft-delete de un tipo de vehículo y registro en historial.
	 * @return bool
	 */
	function softDeleteTipo($conn, $id, $usuarioId) {
	    $sql = "UPDATE tipo_vehiculo
	            SET fecha_borrado = NOW()
	            WHERE id = ? AND fecha_borrado IS NULL";
	    $stmt = $conn->prepare($sql);
	    $stmt->bind_param("i", $id);
	    $ok = $stmt->execute();
	    if ($ok && $stmt->affected_rows > 0) {
	        $cambio = "Marcado como borrado";
	        registrarHistorialTipo($conn, $id, $usuarioId, $cambio);
	    }
	    $stmt->close();
	    return $ok;
		}

	/**
	 * Inserta un registro de cambio en la tabla de historial.
	 */
	function registrarHistorialTipo($conn, $tipoId, $usuarioId, $cambio) {
	    $sql = "INSERT INTO tipo_vehiculo_historial
	            (tipo_id, usuario_id, cambio, fecha)
	            VALUES (?, ?, ?, NOW())";
	    $stmt = $conn->prepare($sql);
	    $stmt->bind_param("iis", $tipoId, $usuarioId, $cambio);
	    $stmt->execute();
	    $stmt->close();
		}



