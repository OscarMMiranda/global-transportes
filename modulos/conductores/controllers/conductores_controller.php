<?php
// archivo: /modulos/conductores/controllers/conductores_controller.php

/**
 * Prepara un statement seguro
 */
function prep($conn, $sql) {
    if (!$conn || !($conn instanceof mysqli)) {
        throw new Exception("❌ Conexión inválida en prep()\nSQL: $sql");
    }
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("❌ Error en prepare(): ({$conn->errno}) {$conn->error}\nSQL: $sql");
    }
    return $stmt;
}


    /* Lista conductores según estado (activo / inactivo)*/
    function listarConductores($conn, $estado = 'activo') {

        $sql = 
			"SELECT 
				id, nombres, apellidos, dni, licencia_conducir, telefono, correo, direccion,
				distrito_id, provincia_id, departamento_id, activo, created_at, foto
			FROM conductores";

		if ($estado === 'activo') {
			$sql .= " WHERE activo = 1";
    		} elseif ($estado === 'inactivo') {
        	$sql .= " WHERE activo = 0";
    		}

    	$sql .= " ORDER BY apellidos, nombres";

    	$stmt = prep($conn, $sql);

    	if (!$stmt->execute()) {
			throw new Exception("❌ Error en execute(): {$stmt->error}");
    	}

    	$stmt->bind_result(
        	$id, $nombres, $apellidos, $dni, $licencia, $telefono, $correo,
        	$direccion, $distrito_id, $provincia_id, $departamento_id,
			$activo, $created_at, $foto
    		);

		$rows = [];

	while ($stmt->fetch()) {
		$rows[] = [
			'id'                => $id,
			'nombres'           => $nombres,
            'apellidos'         => $apellidos,
            'dni'               => $dni,
            'licencia_conducir' => $licencia,
            'telefono'          => $telefono,
            'correo'            => $correo,
            'direccion'         => $direccion,
            'distrito_id'       => $distrito_id,
            'provincia_id'      => $provincia_id,
            'departamento_id'   => $departamento_id,
            'activo'            => (int)$activo,
            'created_at'        => $created_at,
            'foto'              => $foto
        ];
    }

    $stmt->close();
    return $rows;
}

	/** Obtiene un conductor por ID*/
	function obtenerConductorPorId($conn, $id) {

    	$stmt = prep($conn, 
		"SELECT id, nombres, apellidos, dni, licencia_conducir, telefono, correo, direccion,
        	    distrito_id, provincia_id, departamento_id, activo, created_at, foto
        FROM conductores
        WHERE id = ?
    	");

    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        error_log("❌ Error en execute: " . $stmt->error);
        return null;
    }

    $stmt->bind_result(
        $id_c, $nombres, $apellidos, $dni, $licencia, $telefono, $correo,
        $direccion, $distrito_id, $provincia_id, $departamento_id,
        $activo, $created_at, $foto
    );

    if ($stmt->fetch()) {

        $data = [
            'id'                => $id_c,
            'nombres'           => $nombres,
            'apellidos'         => $apellidos,
            'dni'               => $dni,
            'licencia_conducir' => $licencia,
            'telefono'          => $telefono,
            'correo'            => $correo,
            'direccion'         => $direccion,
            'distrito_id'       => $distrito_id,
            'provincia_id'      => $provincia_id,
            'departamento_id'   => $departamento_id,
            'activo'            => (int)$activo,
            'created_at'        => $created_at,
            'foto'              => $foto
        ];

        $stmt->close();
        return $data;
    }

    $stmt->close();
    return null;
}

/**
 * Desactiva (soft delete)
 */
function eliminarConductor($conn, $id) {
    $stmt = prep($conn, "UPDATE conductores SET activo = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok ? '' : "❌ Error al desactivar conductor: {$stmt->error}";
}

/**
 * Restaura conductor
 */
function restaurarConductor($conn, $id) {
    $stmt = prep($conn, "UPDATE conductores SET activo = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok ? '' : "❌ Error al restaurar conductor: {$stmt->error}";
}

/**
 * Eliminación definitiva
 */
function eliminarConductorPermanentemente($conn, $id) {
    $stmt = prep($conn, "DELETE FROM conductores WHERE id = ?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok ? '' : "❌ Error al eliminar conductor: {$stmt->error}";
}
/**
 * Guarda o actualiza un conductor (crear/editar) de forma modular.
 * Retorna array con success/error/data.
 */
function guardarConductor($conn, $post, $files)
{
    // -------------------------------------------------------------
    // VALIDACIONES BÁSICAS
    // -------------------------------------------------------------
    if (!$conn || !($conn instanceof mysqli)) {
        return ['success' => false, 'error' => '❌ Conexión inválida'];
    }

    $id        = isset($post['id']) ? intval($post['id']) : 0;
    $nombres   = isset($post['nombres']) ? trim($post['nombres']) : '';
    $apellidos = isset($post['apellidos']) ? trim($post['apellidos']) : '';
    $dni       = isset($post['dni']) ? trim($post['dni']) : '';
    $licencia  = isset($post['licencia_conducir']) ? trim($post['licencia_conducir']) : '';
    $telefono  = isset($post['telefono']) ? trim($post['telefono']) : '';
    $correo    = isset($post['correo']) ? trim($post['correo']) : '';
    $direccion = isset($post['direccion']) ? trim($post['direccion']) : '';

    $departamento_id = isset($post['departamento_id']) ? intval($post['departamento_id']) : 0;
    $provincia_id    = isset($post['provincia_id']) ? intval($post['provincia_id']) : 0;
    $distrito_id     = isset($post['distrito_id']) ? intval($post['distrito_id']) : 0;

    $activo = isset($post['activo']) ? 1 : 0;

    $fotoActualPost = isset($post['foto_actual']) ? trim($post['foto_actual']) : '';

    $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'sistema';

    // -------------------------------------------------------------
    // OBTENER DATOS ANTERIORES (para historial)
    // -------------------------------------------------------------
    $anterior = ($id > 0) ? obtenerConductorPorId($conn, $id) : null;

    // -------------------------------------------------------------
    // MANEJO DE FOTO
    // -------------------------------------------------------------
    $basePath         = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . "/uploads/conductores";
    $carpetaPrincipal = $basePath . "/";
    $carpetaHistorial = $basePath . "/historial/";

    if (!is_dir($carpetaPrincipal)) mkdir($carpetaPrincipal, 0777, true);
    if (!is_dir($carpetaHistorial)) mkdir($carpetaHistorial, 0777, true);

    $fotoNueva        = null;
    $rutaFotoAnterior = null;

    // FOTO NUEVA
    if (
        isset($files['foto']) &&
        $files['foto']['error'] === UPLOAD_ERR_OK &&
        !empty($files['foto']['name']) &&
        $files['foto']['size'] > 0
    ) {
        $ext = pathinfo($files['foto']['name'], PATHINFO_EXTENSION);
        if (!$ext) $ext = "png";

        $fotoNueva = "conductor_" . $dni . "_" . time() . "." . $ext;
        $destino   = $carpetaPrincipal . $fotoNueva;

        if (!move_uploaded_file($files['foto']['tmp_name'], $destino)) {
            return ['success' => false, 'error' => '❌ No se pudo guardar la foto'];
        }

        // Mover foto anterior al historial
        if ($anterior && !empty($anterior['foto'])) {
            $rutaAnterior = $carpetaPrincipal . $anterior['foto'];
            if (is_file($rutaAnterior)) {
                $rutaFotoAnterior = $carpetaHistorial . "HIST_" . time() . "_" . $anterior['foto'];
                @rename($rutaAnterior, $rutaFotoAnterior);
            }
        }

    } else {
        // CONSERVAR FOTO ANTERIOR
        if (!empty($fotoActualPost)) {
            $fotoNueva = basename($fotoActualPost);
        } elseif ($anterior && !empty($anterior['foto'])) {
            $fotoNueva = $anterior['foto'];
        } else {
            $fotoNueva = null;
        }
    }

    // -------------------------------------------------------------
    // GUARDAR EN BD (INSERT / UPDATE)
    // -------------------------------------------------------------
    if ($id > 0) {

        $stmt = prep($conn, "
            UPDATE conductores SET 
                nombres = ?, apellidos = ?, dni = ?, licencia_conducir = ?, telefono = ?, correo = ?, direccion = ?, 
                departamento_id = ?, provincia_id = ?, distrito_id = ?, activo = ?, foto = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "sssssssiiiisi",
            $nombres, $apellidos, $dni, $licencia, $telefono, $correo, $direccion,
            $departamento_id, $provincia_id, $distrito_id, $activo, $fotoNueva, $id
        );

        $accion = "editar";

    } else {

        $stmt = prep($conn, "
            INSERT INTO conductores 
                (nombres, apellidos, dni, licencia_conducir, telefono, correo, direccion, 
                 departamento_id, provincia_id, distrito_id, activo, foto)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssssssiiiis",
            $nombres, $apellidos, $dni, $licencia, $telefono, $correo, $direccion,
            $departamento_id, $provincia_id, $distrito_id, $activo, $fotoNueva
        );

        $accion = "crear";
    }

    if (!$stmt->execute()) {
        $error = $stmt->error;
        $stmt->close();
        return ['success' => false, 'error' => $error];
    }

    if ($id === 0) $id = $stmt->insert_id;
    $stmt->close();

    // -------------------------------------------------------------
    // REGISTRAR HISTORIAL
    // -------------------------------------------------------------
    $cambios = [];

    if ($anterior) {
        $campos = [
            'nombres','apellidos','dni','licencia_conducir','correo','telefono','direccion',
            'departamento_id','provincia_id','distrito_id','activo','foto'
        ];

        foreach ($campos as $campo) {
            $nuevoValor    = ($campo === 'licencia_conducir') ? $licencia : ($campo === 'foto' ? $fotoNueva : (isset($$campo) ? $$campo : null));
            $valorAnterior = isset($anterior[$campo]) ? $anterior[$campo] : null;

            if ($valorAnterior != $nuevoValor) {
                $cambios[$campo] = [$valorAnterior, $nuevoValor];
            }
        }
    }

    $jsonCambios = json_encode($cambios);

    $stmtH = prep($conn, "
        INSERT INTO conductores_historial 
            (id_registro, modulo, nombres, apellidos, dni, licencia_conducir, correo, telefono, direccion, activo, foto, ruta_foto_anterior, accion, cambios_json, usuario)
        VALUES (?, 'conductores', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmtH->bind_param(
        "isssssssissssss",
        $id, $nombres, $apellidos, $dni, $licencia, $correo, $telefono, $direccion,
        $activo, $fotoNueva, $rutaFotoAnterior, $accion, $jsonCambios, $usuario
    );

    $stmtH->execute();
    $stmtH->close();

    // -------------------------------------------------------------
    // RETORNO MODULAR
    // -------------------------------------------------------------
    return [
        'success' => true,
        'data' => [
            'id'   => $id,
            'foto' => $fotoNueva ? "/uploads/conductores/" . $fotoNueva : null
        ]
    ];
}
