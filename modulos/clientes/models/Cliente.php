<?php
// public_html/modulos/clientes/models/Cliente.php

class Cliente
{
    /** @var mysqli Conexión activa */
    protected static $conn;

    /**
     * Inicializa la conexión (llámalo en el controller)
     * @param mysqli $mysqli
     */
    public static function init($mysqli)
    	{
        self::$conn = $mysqli;
    	}

    /**
     * Devuelve todos los clientes activos, con nombres de ubigeo.
     * @return array
     */
    public static function all()
    	{
        $rows = array();
        $sql = "
            SELECT
              c.id,
              c.nombre,
              c.ruc,
              c.direccion,
              c.correo,
              dpt.nombre AS departamento,
              prv.nombre AS provincia,
              dts.nombre AS distrito
            FROM clientes c
            LEFT JOIN departamentos dpt ON c.departamento_id = dpt.id
            LEFT JOIN provincias  prv ON c.provincia_id    = prv.id
            LEFT JOIN distritos   dts ON c.distrito_id     = dts.id
            WHERE c.estado = ?
            ORDER BY c.nombre
        ";
        if (! $stmt = self::$conn->prepare($sql)) {
            error_log("Cliente::all prepare failed: " . self::$conn->error);
            return $rows;
        }
        $estado = 'Activo';
        $stmt->bind_param('s', $estado);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($fila = $result->fetch_assoc()) {
            $rows[] = $fila;
        }
        $stmt->close();
        return $rows;
    }

    /**
     * Recupera un cliente por ID (para edición), sin joins.
     * @param int $id
     * @return array|null
     * @throws Exception
     */
    public static function find($id)
    {
        $sql = "
            SELECT
              id,
              nombre,
              ruc,
              direccion,
              correo,
              telefono,
              departamento_id,
              provincia_id,
              distrito_id
            FROM clientes
            WHERE id = ?
            LIMIT 1
        ";
        if (! $stmt = self::$conn->prepare($sql)) {
            throw new Exception("Cliente::find prepare failed: " . self::$conn->error);
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = ($res->num_rows === 1) ? $res->fetch_assoc() : null;
        $stmt->close();
        return $row;
    }

    /**
     * Recupera un cliente con nombres de departamento, provincia y distrito.
     * @param int $id
     * @return array|null
     * @throws Exception
     */
    public static function findWithUbigeo($id)
    {
        $sql = "
            SELECT
              c.id,
              c.nombre,
              c.ruc,
              c.direccion,
              c.telefono,
              c.correo,
              c.departamento_id,
              c.provincia_id,
              c.distrito_id,
              dpt.nombre AS departamento,
              prv.nombre AS provincia,
              dts.nombre AS distrito
            FROM clientes c
            LEFT JOIN departamentos dpt ON c.departamento_id = dpt.id
            LEFT JOIN provincias  prv ON c.provincia_id    = prv.id
            LEFT JOIN distritos   dts ON c.distrito_id     = dts.id
            WHERE c.id = ?
            LIMIT 1
        ";
        if (! $stmt = self::$conn->prepare($sql)) {
            throw new Exception("Cliente::findWithUbigeo prepare failed: " . self::$conn->error);
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res     = $stmt->get_result();
        $cliente = ($res->num_rows === 1) ? $res->fetch_assoc() : null;
        $stmt->close();
        return $cliente;
    }

    /**
     * Inserta o actualiza un cliente.
     * @param array $data  Campos del cliente (ver documentación interna)
     * @return bool        true si tuvo éxito
     * @throws Exception   En validación o error de BD
     */
    public static function save($data)
    {
        // 0) Validaciones previas
        if (empty($data['nombre'])) {
            throw new Exception('El nombre es obligatorio.');
        	}

		// 1) Sanitizar y comprobar duplicado de RUC
        $ruc = trim($data['ruc']);
        $sqlChk = "
          	SELECT id FROM clientes
          	WHERE ruc = ?
            AND ( ? IS NULL OR id <> ? )
          	LIMIT 1
        	";
		$stmtChk = self::$conn->prepare($sqlChk);
        if (! $stmtChk) {
            throw new Exception('Error preparando check RUC: ' . self::$conn->error);
        	}
        $nullId = empty($data['id']) ? null : (int)$data['id'];
        $stmtChk->bind_param('sii', $ruc, $nullId, $nullId);
        $stmtChk->execute();
        $resChk = $stmtChk->get_result();
		if ($resChk && $resChk->num_rows) {
            throw new Exception('Ya existe un cliente con este RUC.');
        }
        $stmtChk->close();



        if (
            empty($data['departamento_id']) ||
            empty($data['provincia_id'])    ||
            empty($data['distrito_id'])
        	) 
			{
            throw new Exception('Debe seleccionar departamento, provincia y distrito.');
        	}

        // 2) Prepara campos
        $id     = !empty($data['id']) ? (int) $data['id'] : null;
        $nombre = trim($data['nombre']);
        $ruc    = trim($data['ruc']);
        $dir    = trim($data['direccion']);
        $mail   = trim($data['correo']);
        $tel    = trim($data['telefono']);
        $depId  = (int) $data['departamento_id'];
        $provId = (int) $data['provincia_id'];
        $distId = (int) $data['distrito_id'];

        // 3) Construye SQL
        if ($id) {
            $sql = "
                UPDATE clientes SET
                  nombre=?,
                  ruc=?,
                  direccion=?,
                  correo=?,
                  telefono=?,
                  departamento_id=?,
                  provincia_id=?,
                  distrito_id=?
                WHERE id=?
            ";
            $stmt = self::$conn->prepare($sql);
            $stmt->bind_param(
                'ssssssssi',
                $nombre, 
				$ruc, 
				$dir, 
				$mail, 
				$tel,
                $depId, 
				$provId, 
				$distId,
                $id
            	);
        	} 
		else {
            $sql = "
                INSERT INTO clientes
                  (nombre, ruc, direccion, correo, telefono,
                   departamento_id, provincia_id, distrito_id, estado)
                VALUES
                  (?, ?, ?, ?, ?, ?, ?, ?, 'Activo')
            ";
            $stmt = self::$conn->prepare($sql);
            $stmt->bind_param(
                'ssssssss',
                $nombre, $ruc, $dir, $mail, $tel,
                $depId, $provId, $distId
            );
        }

        // 4) Ejecuta y comprueba
        if (! $stmt->execute()) {
            $err = self::$conn->error;
            $stmt->close();
            throw new Exception("Error en la base de datos: $err");
        }
        $stmt->close();
        return true;
    }

    /**
     * Elimina lógicamente un cliente (marca estado = 'Inactivo').
     * @param int $id
     * @return bool
     */
    public static function delete($id)
    {
        $sql = "UPDATE clientes SET estado = 'Inactivo' WHERE id = ?";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }


	/**
 	* Devuelve clientes marcados como Inactivo.
 	* @return array
 	*/
	public static function allDeleted()
		{
    	$rows = array();
    	$sql = "
      		SELECT 
				id, 
				nombre, 
				ruc, 
				direccion, 
				correo, 
				telefono
      		FROM clientes
      		WHERE estado = 'Inactivo'
      		ORDER BY id DESC
    		";

		$stmt = self::$conn->prepare($sql);
    	if (! $stmt) {
        	error_log("Cliente::allDeleted prepare failed: " . self::$conn->error);
        	return $rows;
    		}
		$stmt->execute();
    	$stmt->bind_result($id, $nombre, $ruc, $direccion, $correo, $telefono);

    	while ($stmt->fetch()) {
        	$rows[] = [
            	'id'        => $id,
            	'nombre'    => $nombre,
            	'ruc'       => $ruc,
            	'direccion' => $direccion,
            	'correo'    => $correo,
            	'telefono'  => $telefono
        		];
    		}

    	$stmt->close();
    	return $rows;
    		// $res = self::$conn->query($sql);
    		// while ($fila = $res->fetch_assoc()) {
    		// 	$rows[] = $fila;
    		// 	}
    		// return $rows;
		}

	/**
 	* Restaura un cliente (estado → 'Activo').
 	* @param int $id
 	* @return bool
 	*/
	public static function restore($id)
		{
    	$sql = "
        	UPDATE clientes
        	SET estado = 'Activo', deleted_at = NULL
        	WHERE id = ?
    	";
    	$stmt = self::$conn->prepare($sql);
    	if (! $stmt) {
        	error_log("Cliente::restore prepare failed: " . self::$conn->error);
        	return false;
    		}

    	$stmt->bind_param('i', $id);
    	$ok = $stmt->execute();
    	$stmt->close();
    	return $ok;
		}

}

