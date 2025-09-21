<?php
	//	archivo	:	/modulos/mantenimiento/entidades/controllers/VerEntidadModal.php

	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
	$conn = getConnection();

	$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
	if ($id <= 0 || !($conn instanceof mysqli)) {
  		echo '<div class="modal-body">ID inválido o conexión fallida.</div>';
  		exit;
		}

	$sql = 
	"SELECT 
    	e.nombre, e.ruc, e.direccion, e.estado,
    	d.nombre AS departamento,
    	p.nombre AS provincia,
    	di.nombre AS distrito
  	FROM entidades e
  	LEFT JOIN departamentos d ON e.departamento_id = d.id
  	LEFT JOIN provincias p ON e.provincia_id = p.id
  	LEFT JOIN distritos di ON e.distrito_id = di.id
  	WHERE e.id = ?";
		
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$e = $result->fetch_assoc();
	$stmt->close();

	if (!$e) {
  		echo '<div class="modal-body">Entidad no encontrada.</div>';
  		exit;
		}
?>

<div class="modal-header">
  	<button type="button" class="close" data-dismiss="modal">&times;</button>
  	<h4 class="modal-title"><i class="fa fa-building"></i> <?= htmlspecialchars($e['nombre']) ?></h4>
</div>
<div class="modal-body">
  	<p><strong>RUC:</strong> <?= htmlspecialchars($e['ruc']) ?></p>
  	<p><strong>Dirección:</strong> <?= htmlspecialchars($e['direccion']) ?></p>
  	<p><strong>Departamento:</strong> <?= htmlspecialchars($e['departamento']) ?></p>
	<p><strong>Provincia:</strong> <?= htmlspecialchars($e['provincia']) ?></p>
	<p><strong>Distrito:</strong> <?= htmlspecialchars($e['distrito']) ?></p>
  	<p><strong>Estado:</strong> <?= htmlspecialchars($e['estado']) ?></p>
</div>
<div class="modal-footer">
  	<button type="button" class="btn btn-default" data-dismiss="modal">
    	<i class="fa fa-times"></i> Cerrar
  	</button>
</div>