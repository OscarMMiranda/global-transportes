<?php
// archivo: /modulos/mantenimiento/tipo_documento/controllers/tipo_documento_controller.php

require_once __DIR__ . '/../../../../includes/config.php';

function listarCategoriasDocumento() {
  
	$conn = getConnection();
	if (!$conn) die("❌ Conexión no disponible.");
	
  	$sql = "SELECT id, nombre FROM categorias_documento WHERE estado = 1 ORDER BY nombre";
  	$stmt = $conn->prepare($sql);
  	if (!$stmt) die("Error en prepare listarCategoriasDocumento: " . $conn->error);
  	$stmt->execute();
  	$res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  	$stmt->close();
  	return $res;
	}

function listarTiposDocumento() {
  	$conn = getConnection();
	if (!$conn) die("❌ Conexión no disponible.");
  	$sql = 
		"SELECT 
      		td.id,
      		cd.nombre AS categoria,
      		td.nombre,
      		td.descripcion,
      		td.estado,
      		td.duracion_meses,
      		td.requiere_inicio,
      		td.requiere_vencimiento,
      		td.requiere_archivo,
      		td.codigo_interno,
      		td.color_etiqueta,
      		td.icono,
      		td.grupo,
      		td.version
    	FROM tipos_documento td
    	JOIN categorias_documento cd ON td.categoria_id = cd.id
    	ORDER BY cd.nombre, td.nombre
  		";
  	$stmt = $conn->prepare($sql);
  	if (!$stmt) die("Error prepare listarTiposDocumento: " . $conn->error);
  	$stmt->execute();
  	$res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  	$stmt->close();
  	return $res;
	}

function obtenerTipoDocumento($id) {
  	$conn = getConnection();
	if (!$conn) die("❌ Conexión no disponible.");
  	$id = (int)$id;
  	$stmt = $conn->prepare(
		"SELECT id, categoria_id, nombre, descripcion, estado,
           	duracion_meses, 
			requiere_inicio, 
			requiere_vencimiento,
           	requiere_archivo, 
			validacion_externa, codigo_interno, 
		   	color_etiqueta, icono, grupo
    	FROM 	tipos_documento 
		WHERE 	id = ?
  		");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $tipo = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  return $tipo ?: [
    'id' => 0,
    'categoria_id' => 0,
    'nombre' => '',
    'descripcion' => '',
    'estado' => 1,
    'duracion_meses' => '',
    'requiere_inicio' => 0,
    'requiere_vencimiento' => 0,
    'requiere_archivo' => 0,
    'codigo_interno' => '',
    'color_etiqueta' => '#ffffff',
    'icono' => '',
    'grupo' => ''
  ];
}

function procesarTipoDocumento($post) {
	$conn = getConnection();
	if (!$conn) die("❌ Conexión no disponible.");  $id = (int) $post['id'];
  
	$categoria_id = (int) $post['categoria_id'];
  $nombre       = isset($post['nombre']) ? trim($post['nombre']) : '';
  $descripcion  = isset($post['descripcion']) ? trim($post['descripcion']) : '';
  $duracion     = isset($post['duracion_meses']) ? (int)$post['duracion_meses'] : 0;

  $requiere_inicio      = isset($post['requiere_inicio']) ? 1 : 0;
  $requiere_vencimiento = isset($post['requiere_vencimiento']) ? 1 : 0;
  $requiere_archivo     = isset($post['requiere_archivo']) ? 1 : 0;
  $codigo_interno       = isset($post['codigo_interno']) ? trim($post['codigo_interno']) : '';
  $color_etiqueta       = isset($post['color_etiqueta']) ? trim($post['color_etiqueta']) : '#ffffff';
  $icono                = isset($post['icono']) ? trim($post['icono']) : '';
  $grupo                = isset($post['grupo']) ? trim($post['grupo']) : '';
  $usuario              = isset($_SESSION['usuario_id']) ? (int)$_SESSION['usuario_id'] : null;

  if (!$categoria_id) return '❌ Debes elegir una categoría.';
  if ($nombre === '') return '❌ El nombre es obligatorio.';

  $sqlChk = $id > 0
    ? "SELECT id FROM tipos_documento WHERE nombre = ? AND id <> ?"
    : "SELECT id FROM tipos_documento WHERE nombre = ?";
  $stmt = $conn->prepare($sqlChk);
  if ($id > 0) $stmt->bind_param("si", $nombre, $id);
  else         $stmt->bind_param("s", $nombre);
  $stmt->execute();
  if ($stmt->get_result()->num_rows) {
    $stmt->close();
    return '❌ Ya existe un tipo con ese nombre.';
  }
  $stmt->close();

  if ($id > 0) {
    $stmt = $conn->prepare("
      UPDATE tipos_documento SET
        categoria_id = ?, nombre = ?, descripcion = ?, duracion_meses = ?,
        requiere_inicio = ?, requiere_vencimiento = ?, requiere_archivo = ?,
        codigo_interno = ?, color_etiqueta = ?, icono = ?, grupo = ?,
        usuario_editor = ?, fecha_actualizacion = NOW(),
        version = version + 1
      WHERE id = ?
    ");
    $stmt->bind_param(
      "issiiiiisssii",
      $categoria_id, $nombre, $descripcion, $duracion,
      $requiere_inicio, $requiere_vencimiento, $requiere_archivo,
      $codigo_interno, $color_etiqueta, $icono, $grupo,
      $usuario, $id
    );
  } else {
    $stmt = $conn->prepare("
      INSERT INTO tipos_documento (
        categoria_id, nombre, descripcion, estado, duracion_meses,
        requiere_inicio, requiere_vencimiento, requiere_archivo,
        codigo_interno, color_etiqueta, icono, grupo,
        version, origen, usuario_creador, fecha_creacion
      ) VALUES (?, ?, ?, 1, ?, ?, ?, ?, ?, ?, ?, ?, 1, 'manual', ?, NOW())
    ");
    $stmt->bind_param(
      "issiiiiissssi",
      $categoria_id, $nombre, $descripcion, $duracion,
      $requiere_inicio, $requiere_vencimiento, $requiere_archivo,
      $codigo_interno, $color_etiqueta, $icono, $grupo,
      $usuario
    );
  }

  if (!$stmt->execute()) {
    $err = $stmt->error;
    $stmt->close();
    return "❌ Error al guardar: $err";
  }

  $stmt->close();
  return '';
}

function listarTiposDocumentoPorEstado($estado = 1) {
  	$conn = getConnection();
	if (!$conn) die("❌ Conexión no disponible.");
  		$estado = (int)$estado;
  		$sql = 
		"SELECT 
      		td.id,
      		cd.nombre AS categoria,
      		td.nombre,
      		td.descripcion,
      		td.estado,
      		td.duracion_meses,
      		td.requiere_inicio,
      		td.requiere_vencimiento,
      		td.requiere_archivo,
      		td.codigo_interno,
      		td.color_etiqueta,
      		td.icono,
      		td.grupo,
      		td.version
    	FROM tipos_documento td
    	JOIN categorias_documento cd ON td.categoria_id = cd.id
    	WHERE td.estado = ?
    	ORDER BY cd.nombre, td.nombre
  		";
  	$stmt = $conn->prepare($sql);
  	if (!$stmt) die("Error prepare listarTiposDocumentoPorEstado: " . $conn->error);
  	$stmt->bind_param("i", $estado);
  	$stmt->execute();
  	$res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  	$stmt->close();
  	return $res;
	}