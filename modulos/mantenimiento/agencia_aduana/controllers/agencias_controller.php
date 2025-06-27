<?php
// /modulos/mantenimiento/agencia_aduana/controllers/agencias_controller.php

// session_start();
require_once __DIR__ . '/../../../../includes/conexion.php';
require_once __DIR__ . '/../../../../includes/funciones.php';

/**
 * Plantilla vacía de agencia.
 */
function agenciaVacia() {
    return array(
        'id'              => 0,
        'nombre'          => '',
        'ruc'             => '',
        'direccion'       => '',
        'departamento_id' => 0,
        'provincia_id'    => 0,
        'distrito_id'     => 0,
        'telefono'        => '',
        'correo_general'  => '',
        'contacto'        => ''
    );
}

/**
 * Listar departamentos.
 */
function listarDepartamentos() {
    global $conn;
    $stmt = $conn->prepare("SELECT id, nombre FROM departamentos ORDER BY nombre");
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $res;
}

/**
 * Listar provincias.
 */
function listarProvincias() {
    global $conn;
    $stmt = $conn->prepare("SELECT id, nombre, departamento_id FROM provincias ORDER BY nombre");
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $res;
}

/**
 * Listar distritos.
 */
function listarDistritos() {
    global $conn;
    $stmt = $conn->prepare("SELECT id, nombre, provincia_id FROM distritos ORDER BY nombre");
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $res;
}

/**
 * Listar todas las agencias con datos de Ubigeo.
 */
function listarAgencias() {
    global $conn;
    $sql = "
      SELECT 
        a.id, a.nombre, a.ruc, a.direccion,
        d.nombre AS departamento_nombre,
        p.nombre AS provincia_nombre,
        di.nombre AS distrito_nombre,
        a.estado, a.fecha_creacion
      FROM agencias_aduanas a
      LEFT JOIN departamentos d ON a.departamento_id = d.id
      LEFT JOIN provincias  p ON a.provincia_id    = p.id
      LEFT JOIN distritos   di ON a.distrito_id     = di.id
      ORDER BY a.nombre
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $res;
}

/**
 * Obtener un registro por ID.
 */
function obtenerRegistro($id) {
    global $conn;
    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM agencias_aduanas WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $fila = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!is_array($fila)) {
        return agenciaVacia();
    }
    return array_merge(agenciaVacia(), $fila);
}

/**
 * Procesar alta/edición/reactivación.
 * Devuelve cadena vacía si OK o mensaje de error.
 */
function procesarFormulario($post) {
    global $conn;
    // Sanitizar y castear
    $id              = isset($post['id'])              ? (int) $post['id']              : 0;
    $nombre          = isset($post['nombre'])          ? trim($post['nombre'])          : '';
    $ruc             = isset($post['ruc'])             ? trim($post['ruc'])             : '';
    $direccion       = isset($post['direccion'])       ? trim($post['direccion'])       : '';
    $departamento_id = isset($post['departamento_id']) ? (int) $post['departamento_id'] : 0;
    $provincia_id    = isset($post['provincia_id'])    ? (int) $post['provincia_id']    : 0;
    $distrito_id     = isset($post['distrito_id'])     ? (int) $post['distrito_id']     : 0;
    $telefono        = isset($post['telefono'])        ? trim($post['telefono'])        : '';
    $correo          = isset($post['correo_general'])  ? trim($post['correo_general'])  : '';
    $contacto        = isset($post['contacto'])        ? trim($post['contacto'])        : '';

    // Validación básica
    if ($nombre === '' || $ruc === '') {
        return '❌ El nombre y el RUC son obligatorios.';
    	}

	if ($correo && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    	return '❌ El correo no tiene un formato válido.';
		}


    // 1) Edición de existente
    if ($id > 0) {
        // Chequear duplicados RUC en otro registro activo
        $chk = $conn->prepare(
            "SELECT id FROM agencias_aduanas WHERE ruc = ? AND id <> ? AND estado = 1"
        );
        $chk->bind_param("si", $ruc, $id);
        $chk->execute();
        if ($chk->get_result()->num_rows > 0) {
            $chk->close();
            return '❌ Ese RUC ya está registrado en otra agencia activa.';
        }
        $chk->close();

		$sql = "
  			UPDATE agencias_aduanas SET
    			nombre          = ?,
    			ruc             = ?,
    			direccion       = ?,
    			departamento_id = ?,
    			provincia_id    = ?,
    			distrito_id     = ?,
    			telefono        = ?,
    			correo_general  = ?,
    			contacto        = ?
  			WHERE id = ?
			";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssiiisssi",    // 10 tipos: s,s,s,i,i,i,s,s,s,i
        	$nombre, 
			$ruc, 
			$direccion,
            $departamento_id, 
			$provincia_id, 
			$distrito_id,
            $telefono, 
			$correo, 
			$contacto,
            $id
        );
        if (!$stmt->execute()) {
            $err = $stmt->error;
            $stmt->close();
            return "❌ Error al actualizar: $err";
        }
        $stmt->close();
        registrarEnHistorial(
            $_SESSION['usuario'],
            "Editó agencia aduana (ID: $id)",
            'agencias_aduanas',
            $_SERVER['REMOTE_ADDR']
        );
        return '';
    }

    // 2) Reactivar si existe en estado=0
    $chk = $conn->prepare(
        "SELECT id FROM agencias_aduanas WHERE ruc = ? AND estado = 0"
    );
    $chk->bind_param("s", $ruc);
    $chk->execute();
    $res = $chk->get_result();
    $chk->close();
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $aid = (int)$row['id'];
        $sql = "
          UPDATE agencias_aduanas SET
            estado          = 1,
            nombre          = ?,
            direccion       = ?,
            departamento_id = ?,
            provincia_id    = ?,
            distrito_id     = ?,
            telefono        = ?,
            correo_general  = ?,
            contacto        = ?
          WHERE id = ?
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssiiisssi",    // 9 tipos: s,s,i,i,i,s,s,s,i
            $nombre, 
			$direccion,
            $departamento_id, 
			$provincia_id, 
			$distrito_id,
            $telefono, 
			$correo, 
			$contacto,
            $aid
        );
        if (!$stmt->execute()) {
            $err = $stmt->error;
            $stmt->close();
            return "❌ Error al reactivar: $err";
        }
        $stmt->close();
        registrarEnHistorial(
            $_SESSION['usuario'],
            "Reactivó agencia aduana (ID: $aid)",
            'agencias_aduanas',
            $_SERVER['REMOTE_ADDR']
        );
        return '';
    }


	// 3) **EVITAR INSERTAR SI YA ESTÁ ACTIVA**  
    	$chkActivo = $conn->prepare(
      	"SELECT id 
         	FROM agencias_aduanas 
        	WHERE ruc = ? 
          	AND estado = 1"
    		);
    	$chkActivo->bind_param("s", $ruc);
    	$chkActivo->execute();
    	if ($chkActivo->get_result()->num_rows > 0) {
        	$chkActivo->close();
        	return '❌ Ese RUC ya está registrado en otra agencia activa.';
    	}
    	$chkActivo->close();






    // 4) Insertar nuevo
    $sql = "
      	INSERT INTO agencias_aduanas
        	(nombre, 
			ruc, 
			direccion,
         	departamento_id, 
			provincia_id, 
			distrito_id,
         	telefono, 
			correo_general, 
			contacto)
      	VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    	";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssiiisssi", // ← 9 letras: s,s,i,i,i,s,s,s,i
        $nombre, 
		$ruc, 
		$direccion,
        $departamento_id, 
		$provincia_id, 
		$distrito_id,
        $telefono, 
		$correo, 
		$contacto
    );
    if (!$stmt->execute()) {
        $err = $stmt->error;
        $stmt->close();
        return "❌ Error al insertar: $err";
    }
    $newId = $stmt->insert_id;
    $stmt->close();
    registrarEnHistorial(
        $_SESSION['usuario'],
        "Agregó nueva agencia aduana (ID: $newId)",
        'agencias_aduanas',
        $_SERVER['REMOTE_ADDR']
    );
    return '';
}

/**
 * Eliminación lógica.
 */
function eliminarAgencia($id) {
    global $conn;
    $id = (int)$id;
    $stmt = $conn->prepare("UPDATE agencias_aduanas SET estado = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    registrarEnHistorial(
        $_SESSION['usuario'],
        "Eliminó agencia aduana (ID: $id)",
        'agencias_aduanas',
        $_SERVER['REMOTE_ADDR']
    );
}

/**
 * Reactivar registro.
 */
function reactivarAgencia($id) {
    global $conn;
    $id = (int)$id;
    $stmt = $conn->prepare("UPDATE agencias_aduanas SET estado = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    registrarEnHistorial(
        $_SESSION['usuario'],
        "Reactivó agencia aduana (ID: $id)",
        'agencias_aduanas',
        $_SERVER['REMOTE_ADDR']
    );
}



