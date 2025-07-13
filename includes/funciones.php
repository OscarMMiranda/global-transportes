<?php
	// Verifica si la conexión está establecida
	require_once 'conexion.php';

	/**
	 * Obtener la lista de vehículos desde la base de datos.
	 * @param mysqli $conn Conexión a la base de datos
	 * @return mysqli_result Resultado de la consulta SQL
	 */

	function obtenerVehiculos($conn, $activo = 1) {
		$activo = (int) $activo;
		$sql = 
			"SELECT 
        		v.id, 
				v.placa, 
				v.modelo, 
				v.anio, 
				v.observaciones,
        		m.nombre    AS marca,
        		t.nombre    AS tipo,
        		e.razon_social AS empresa,
        		ev.nombre   AS estado_operativo,
				v.activo 
      			FROM vehiculos v
      			JOIN marca_vehiculo m ON v.marca_id = m.id
      			JOIN tipo_vehiculo  t ON v.tipo_id  = t.id
      			JOIN empresa        e ON v.empresa_id = e.id
      			JOIN estado_vehiculo ev ON v.estado_id = ev.id
      			WHERE v.activo = $activo
      			ORDER BY v.placa
    			";
    	
		$result = $conn->query($sql);
    	if (!$result) {
        	die("Error en obtenerVehiculos: " . $conn->error);
    		}
    	return $result;

		}

	/**
	 * Obtener detalles de un vehículo específico.
	 * @param mysqli $conn Conexión a la base de datos
	 * @param int $id ID del vehículo
	 * @return array|null Datos del vehículo o null si no existe
	 */
	function obtenerVehiculoPorId($conn, $id) {
    	$sql = 
			"SELECT id, placa, marca_id, modelo, tipo_id, anio, empresa_id, activo 
			FROM vehiculos 
			WHERE id = ?";
    	$stmt = $conn->prepare($sql);
    	$stmt->bind_param("i", $id);
    	$stmt->execute();
    	$result = $stmt->get_result();
    	return ($result->num_rows > 0) ? $result->fetch_assoc() : null;
		}

	/**
	 * Registrar un nuevo vehículo en la base de datos.
	 * @param mysqli $conn Conexión a la base de datos
	 * @param string $placa Placa del vehículo
	 * @param int $marca_id ID de la marca
	 * @param string $modelo Modelo del vehículo
	 * @param int $tipo_id ID del tipo
	 * @param int $anio Año del vehículo
	 * @param int $empresa_id ID de la empresa
	 * @return bool Éxito o fallo en la inserción
	 */
	function registrarVehiculo($conn, $placa, $marca_id, $modelo, $tipo_id, $anio, $empresa_id) {
    	$sql = 
			"INSERT INTO vehiculos (placa, marca_id, modelo, tipo_id, anio, empresa_id) 
			VALUES (?, ?, ?, ?, ?, ?)";
    	$stmt = $conn->prepare($sql);
    	$stmt->bind_param("sisiii", $placa, $marca_id, $modelo, $tipo_id, $anio, $empresa_id);
    	return $stmt->execute();
		}

	/**
	 * Editar los datos de un vehículo existente.
	 * @param mysqli $conn Conexión a la base de datos
	 * @param int $id ID del vehículo
	 * @param string $placa Nueva placa
	 * @param int $marca_id Nueva marca
	 * @param string $modelo Nuevo modelo
	 * @param int $tipo_id Nuevo tipo
	 * @param int $anio Nuevo año
	 * @param int $empresa_id Nueva empresa
	 * @return bool Éxito o fallo en la actualización
	 */
	function editarVehiculo($conn, $id, $placa, $marca_id, $modelo, $tipo_id, $anio, $empresa_id) {
    	$sql = 
			"UPDATE vehiculos 
			SET placa = ?, 
				marca_id = ?, 
				modelo = ?, 
				tipo_id = ?, 
				anio = ?, 
				empresa_id = ? 
			WHERE id = ?";
    	$stmt = $conn->prepare($sql);
    	$stmt->bind_param("sisiiii", $placa, $marca_id, $modelo, $tipo_id, $anio, $empresa_id, $id);
		return $stmt->execute();
		}

	/**
	* Eliminar un vehículo por su ID.
 	* @param mysqli $conn Conexión a la base de datos
 	* @param int $id ID del vehículo a eliminar
 	* @return bool Éxito o fallo en la eliminación
 	*/
	function eliminarVehiculo($conn, $id) {
    	// $sql = "DELETE FROM vehiculos WHERE id = ?";
    	// $stmt = $conn->prepare($sql);
    	// $stmt->bind_param("i", $id);
    	// return $stmt->execute();
		 $sql = 
		 	"UPDATE vehiculos 
			SET activo = 0 
			WHERE id = ?";
    	$stmt = $conn->prepare($sql);
    	$stmt->bind_param("i", $id);
    	return $stmt->execute();
		}

	function obtenerMarcaNombre($conn, $marca_id) {
    	$sql = "SELECT nombre FROM marca_vehiculo WHERE id = ?";
    	$stmt = $conn->prepare($sql);
    	$stmt->bind_param("i", $marca_id);
    	$stmt->execute();
    	$result = $stmt->get_result();
    	if ($row = $result->fetch_assoc()) {
        	return $row['nombre'];
    		} 
		else {
        	return "Desconocido"; // Si no se encuentra la marca
    		}
		}


	function obtenerTipoNombre($conn, $tipo_id) {
    	$sql = "SELECT nombre FROM tipo_vehiculo WHERE id = ?";
    	$stmt = $conn->prepare($sql);
    	$stmt->bind_param("i", $tipo_id);
    	$stmt->execute();
    	$result = $stmt->get_result();
    
    	if ($row = $result->fetch_assoc()) {
        	return $row['nombre'];
    		} 
		else {
        	return "Desconocido"; // Si no se encuentra el tipo
    		}
		}

	function obtenerEmpresaNombre($conn, $empresa_id) {
    	$sql = "SELECT razon_social FROM empresa WHERE id = ?";
    	$stmt = $conn->prepare($sql);
    	$stmt->bind_param("i", $empresa_id);
    	$stmt->execute();
    	$result = $stmt->get_result();
    	if ($row = $result->fetch_assoc()) {
        	return $row['razon_social'];
    		} 
		else {
        	return "Desconocida"; // Si no se encuentra la empresa
    		}
		}



	/**
 	* Registrar una acción en el historial de base de datos.
 	* @param string $usuario Usuario que ejecutó la acción
 	* @param string $accion Descripción de la acción realizada
 	* @param string $tabla_afectada Nombre de la tabla afectada (opcional)
 	* @param string $ip Dirección IP del usuario
 	* @return bool Éxito o fallo del registro
 	*/
	function registrarEnHistorial($usuario, $accion, $tabla_afectada = null, $ip) {
    	global $conn;

    	$sql = "INSERT INTO historial_bd (usuario, accion, tabla_afectada, ip_usuario) VALUES (?, ?, ?, ?)";
    	$stmt = $conn->prepare($sql);
    	if (!$stmt) {
    	    error_log("❌ Error al preparar el insert en historial: " . $conn->error);
        	return false;
    		}
    	$stmt->bind_param("ssss", $usuario, $accion, $tabla_afectada, $ip);
    	return $stmt->execute();
	}

	function restaurarVehiculo($conn, $id) {
    $sql = "UPDATE vehiculos SET activo = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}




?>
