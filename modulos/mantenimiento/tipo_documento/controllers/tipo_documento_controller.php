<?php
	require_once __DIR__ . '/../../../../includes/conexion.php';

	// 
	
	function listarCategoriasDocumento() {
    	global $conn;
    	$sql  = "SELECT id, nombre 
				FROM categorias_documento 
				WHERE estado=1 
				ORDER BY nombre";
    	$stmt = $conn->prepare($sql);
    	// if (!$stmt) {
        // 	die("Error en prepare listarCategoriasDocumento: " . $conn->error);
    	// 	}
    	// $stmt->execute();

		if (!$stmt) {
    		die("Error en prepare listarCategoriasDocumento: " . $conn->error);
  			}
  		$stmt->execute();
  		$res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  		$stmt->close();
  		return $res;

		}


// function listarTiposDocumento() {
//   global $conn;
//   $sql = "
//     SELECT td.id, tc.nombre AS categoria, td.nombre, td.descripcion, td.estado
//       FROM tipos_documento td
//       JOIN tipos_categoria tc ON td.categoria_id = tc.id
//      ORDER BY tc.nombre, td.nombre
//   ";
//   $stmt = $conn->prepare($sql);
//   $stmt->execute();
//   $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
//   $stmt->close();
//   return $res;
// }

	function listarTiposDocumento() {
  		global $conn;
  		$sql = "
    		SELECT 
      			td.id,
      			cd.nombre AS categoria,
      			td.nombre,
      			td.descripcion,
      			td.estado
    		FROM tipos_documento td
    		JOIN categorias_documento cd ON td.categoria_id = cd.id
    		ORDER BY cd.nombre, td.nombre
  			";
  		$stmt = $conn->prepare($sql);
  		if (!$stmt) die("Error prepare listarTiposDocumento: ".$conn->error);
  			$stmt->execute();
  		return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
		
		}


	function obtenerTipoDocumento($id) {
  		global $conn;
  		$id = (int)$id;
  		$stmt = $conn->prepare("SELECT * FROM tipos_documento WHERE id = ?");
  		$stmt->bind_param("i",$id);
  		$stmt->execute();
  		$tipo = $stmt->get_result()->fetch_assoc();
  		$stmt->close();
  		return $tipo ?: ['id'=>0,'categoria_id'=>0,'nombre'=>'','descripcion'=>'','estado'=>1];
		}

    function procesarTipoDocumento($post) {
  		global $conn;
  		$id           = (int) $post['id'];
  		$categoria_id = (int) $post['categoria_id'];
		//   $nombre       = trim($post['nombre'] ?? '');
		//   $descripcion  = trim($post['descripcion'] ?? '');
		$nombre      = isset($post['nombre'])      ? trim($post['nombre'])      : '';
		$descripcion = isset($post['descripcion']) ? trim($post['descripcion']) : '';


  // validaciones
  	if(!$categoria_id)  return '❌ Debes elegir una categoría.';
  	if($nombre === '')  return '❌ El nombre es obligatorio.';

  // duplicado
  	$sqlChk = $id>0
    	? "SELECT id FROM tipos_documento WHERE nombre=? AND id<>?"
    	: "SELECT id FROM tipos_documento WHERE nombre=?";
  	$stmt = $conn->prepare($sqlChk);
  	if($id>0) $stmt->bind_param("si",$nombre,$id);
  	else      $stmt->bind_param("s",$nombre);
  		$stmt->execute();
  	if($stmt->get_result()->num_rows) {
    	$stmt->close();
    return '❌ Ya existe un tipo con ese nombre.';
  	}
  	$stmt->close();

  	if($id>0) {
    	// editar
    	$sql = "
      		UPDATE tipos_documento SET
        	categoria_id=?, nombre=?, descripcion=?
      		WHERE id=?
    		";
    	$stmt = $conn->prepare($sql);
    	$stmt->bind_param("issi",$categoria_id,$nombre,$descripcion,$id);
  		} 
	else {
    	// insertar
    	$sql = "
      		INSERT INTO tipos_documento
        	(categoria_id,nombre,descripcion)
      		VALUES(?,?,?)
    		";
    	$stmt = $conn->prepare($sql);
    	$stmt->bind_param("iss",$categoria_id,$nombre,$descripcion);
  		}

  	if(!$stmt->execute()) {
    	$err = $stmt->error;
    	$stmt->close();
    	return "❌ Error al guardar: $err";
  		}
  	$stmt->close();
  	return '';
	}
