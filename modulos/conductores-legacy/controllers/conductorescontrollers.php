<?php
// modulos/conductores/controllers/conductorescontrollers.php

	class ConductoresController
		{
    	private $db;

    	// public function __construct($db)
		public function __construct(mysqli $db)
    		{
        	$this->db = $db;
    		}

    	public function index()
			{
    		$sql  = "SELECT id, 
					dni, 
					nombres, 
					apellidos, 
					licencia_conducir, 
					telefono, 
					correo,
					direccion,
        			foto, 
					activo
            	FROM conductores
            	WHERE activo = 1
            	ORDER BY apellidos, nombres";
    		$res  = $this->db->query($sql);
    		if (!$res) {
    			// return array('error_sql' => $this->db->error);
				return ['success'=>false, 'error'=>$this->db->error];

    			}
    		//return $res->fetch_all(MYSQLI_ASSOC);
			return ['success'=>true, 'data'=>$res->fetch_all(MYSQLI_ASSOC)];
			}
	

    	public function get($id)
    		{
        	$stmt = $this->db->prepare(
            	"SELECT id, 
						dni, 
						nombres, 
						apellidos, 
						licencia_conducir, 
						telefono, 
						correo,
						direccion,
						foto,
						activo
             	FROM conductores
             	WHERE id = ?"
        		);

			if (!$stmt) {
        		return ['success'=>false, 'error'=>$this->db->error];
    		}	
        	$stmt->bind_param("i", $id);
        	$stmt->execute();
        	$row  = $stmt->get_result()->fetch_assoc();
        	$stmt->close();
			// return $row;

			if (!$row) {
            	return ['success'=>false,'error'=>'Conductor no encontrado'];
        		}
			return ['success'=>true, 'data'=>$row];

    		}

    	public function save($data)
    		{
        	// sanitizar
        	$id        = !empty($data['id']) ? (int)$data['id'] : 0;
        	$dni       = $this->db->real_escape_string($data['dni']);
        	$nombres   = $this->db->real_escape_string($data['nombres']);
        	$apellidos = $this->db->real_escape_string($data['apellidos']);
        	$licencia  = $this->db->real_escape_string($data['licencia_conducir']);
        	$telefono  = $this->db->real_escape_string($data['telefono']);
        	$correo    = $this->db->real_escape_string($data['correo']);

			$direccion = isset($data['direccion']) ? 
            			$this->db->real_escape_string($data['direccion']) 
              			: '';
			$foto      = isset($data['foto'])      ? 
              			$this->db->real_escape_string($data['foto']) 
              			: '';

        	$activo    = isset($data['activo']) && $data['activo'] == '1' ? 1 : 0;

        	if ($id > 0) {
            	// UPDATE
            	$stmt = $this->db->prepare(
                	"UPDATE conductores
                 	SET dni = ?, 
				 		nombres = ?, 
						apellidos = ?, 
						licencia_conducir = ?,
                    	telefono = ?, 
						correo = ?,
						direccion = ?,    
         				foto = ?,
						activo = ?
                 	WHERE id = ?"
            		);

				if (!$stmt) return ['success' => false, 'error' => $this->db->error];

            	$stmt->bind_param(
               		"ssssssssii",
                $dni,
				$nombres, 
				$apellidos,
                $licencia, 
				$telefono, 
				$correo,
				$direccion,
      			$foto,
                $activo, 
				$id
            );
        } else {
            // INSERT
            $stmt = $this->db->prepare(
                "INSERT INTO conductores
                   (dni, 
				   	nombres, 
				   	apellidos, 
				   	licencia_conducir, 
					telefono, 
					correo,
					direccion,
					foto,
					activo, 
					created_at)
                 VALUES (?,?,?,?,?,?,?,?,?, NOW())"
            );

			if (!$stmt) return ['success' => false, 'error' => $this->db->error];

            $stmt->bind_param(
                "ssssssssi",
                $dni, 
				$nombres, 
				$apellidos,
                $licencia, 
				$telefono, 
				$correo,
				$direccion,
      			$foto, 
                $activo
            );
        }

		if (!$stmt->execute()) {
            $err = $stmt->error;
            $stmt->close();
            return ['success' => false, 'error' => $err];
        }

		$newId = $id > 0 ? $id : $this->db->insert_id;
        $stmt->close();
        return ['success' => true, 'id' => $newId];
    }


    public function delete($id)
    	{
    	$stmt = $this->db->prepare("UPDATE conductores SET activo = 0 WHERE id = ?");
		if (!$stmt) {
    		return ['success' => false, 'error' => $this->db->error];
			}		
		$stmt->bind_param("i", $id);
    	// $stmt->execute();
    	// $success = ($stmt->affected_rows > 0);
    	// $stmt->close();
    	// return array('success' => $success);

		if (!$stmt->execute()) {
        	$err = $stmt->error;
        	$stmt->close();
        	return ['success' => false, 'error' => $err];
    		}
    	$ok = $stmt->affected_rows > 0;
    	$stmt->close();
    	return ['success' => $ok];
		}


}
