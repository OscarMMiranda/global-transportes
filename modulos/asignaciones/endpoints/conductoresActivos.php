<?php
// conductoresActivos.php

// Devuelve lista de conductores activos en JSON
require_once __DIR__ . '/../../includes/config.php';
// o si usas conexión directa:
// require_once __DIR__ . '/../conexion.php';

if (! isset($db)) {
  	echo json_encode(['error' => 'No existe la conexión $db']);
  	exit;
	}
try {
  	$stmt = $db->prepare("
    	SELECT id, nombre
    	FROM conductores
    	WHERE activo = 1
    	ORDER BY nombre
  		");
	$stmt->execute();
  	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// Si no hay filas, avísalo
  if (empty($rows)) {
    echo json_encode(['warning' => 'No hay conductores activos']);
  } else {
    echo json_encode($rows);
  }
} catch (PDOException $e) {
  echo json_encode(['exception' => $e->getMessage()]);
}


