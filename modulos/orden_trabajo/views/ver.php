<?php
	//	archivo	:	/modulos/orden_trabajo/views/ver.php

	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	session_start();
	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';
	$conn = getConnection();

	// Verificar que se haya pasado el parámetro de la orden
	if (!isset($_GET['orden_trabajo_id'])) {
    	die("No se especificó la orden.");
	}

	$orden_id = intval($_GET['orden_trabajo_id']);

	// Consulta para obtener los datos de la orden (ajusta los JOIN y campos según tu estructura)
	$sql = "SELECT ot.*, 
               c.nombre AS cliente_nombre, c.direccion, 
               tot.nombre AS tipo_ot_nombre, 
               e.razon_social AS empresa_nombre, 
               eo.nombre AS estado_nombre
		FROM ordenes_trabajo ot
		LEFT JOIN clientes c ON ot.cliente_id = c.id
		LEFT JOIN tipo_ot tot ON ot.tipo_ot_id = tot.id
		LEFT JOIN empresa e ON ot.empresa_id = e.id
		LEFT JOIN estado_orden_trabajo eo ON ot.estado_ot = eo.id
		WHERE ot.id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $orden_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$orden = $result->fetch_assoc();
	if (!$orden) {
		die("Orden no encontrada.");
		}
	$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Visualizar Orden de Trabajo</title>
		
		<!-- Bootstrap CSS -->
  		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  		<!-- Tu archivo de estilos (opcional) -->
  		<link rel="stylesheet" href="../../css/orden_trabajo.css">
  		<style>
    		/* Ajustes específicos para la vista del detalle */
    		.detalle-orden p { margin-bottom: 0.5rem; }
  		</style>
	</head>
	<body>
		<div class="container mt-4">
  			<h2 class="text-center text-primary mb-4">Detalle de la Orden de Trabajo</h2>
		
  			<!-- Tarjeta con la información de la orden -->
  			<div class="card shadow-sm mb-4">
    			<div class="card-header bg-info text-white">
      				<h4>Orden Nº: <?= htmlspecialchars($orden['numero_ot']); ?></h4>
    			</div>
    			<div class="card-body detalle-orden">
      				<p><strong>Fecha:</strong> <?= htmlspecialchars($orden['fecha']); ?></p>
      				<p><strong>Cliente:</strong> <?= htmlspecialchars($orden['cliente_nombre']); ?></p>
      				<p><strong>O.C.:</strong> <?= htmlspecialchars($orden['oc_cliente']); ?></p>
      				<p><strong>Tipo OT:</strong> <?= htmlspecialchars($orden['tipo_ot_nombre']); ?></p>

					<?php if ($orden['tipo_ot_id'] == 2 && !empty($orden['numero_dam'])): ?>
    					<p><strong>Número DAM:</strong> <?= htmlspecialchars($orden['numero_dam']); ?></p>
					<?php elseif ($orden['tipo_ot_id'] == 3 && !empty($orden['numero_booking'])): ?>
    					<p><strong>Número Booking:</strong> <?= htmlspecialchars($orden['numero_booking']); ?></p>
					<?php elseif ($orden['tipo_ot_id'] == 1 && !empty($orden['otros'])): ?>
    					<p><strong>Otros:</strong> <?= htmlspecialchars($orden['otros']); ?></p>
					<?php endif; ?>

      				<p><strong>Empresa:</strong> <?= htmlspecialchars($orden['empresa_nombre']); ?></p>
      				<p><strong>Estado:</strong> <?= htmlspecialchars($orden['estado_nombre']); ?></p>
      				<!-- Agrega aquí más detalles que consideres relevantes -->
    			</div>
  			</div>
		
  			<!-- Opciones de acción -->
  			<div class="text-center">
    			<!-- Botón para imprimir -->
    			<button class="btn btn-primary mx-2" onclick="window.print();">Imprimir</button>
    
   	 			<!-- Botón para exportar a PDF (aquí se muestra un alert; se puede integrar jsPDF o redirigir a un script de exportación) -->
    			<button class="btn btn-success mx-2" onclick="exportarPDF();">Exportar a PDF</button>
		
    			<!-- Botón para guardar o editar -->
    			<a href="editar_orden.php?orden_trabajo_id=<?= urlencode($orden['id']); ?>" class="btn btn-warning mx-2">Guardar / Editar</a>


				<a href="crear_viaje.php?numero_ot=<?= urlencode($orden['numero_ot']); ?>" class="btn btn-outline-primary">➕ Agregar Viaje</a>
  			</div>


  
  			<!-- Botón para regresar al listado -->
  			<div class="text-center mt-3">
    			<a href="../index.php" class="btn btn-secondary">← Volver a Órdenes de Trabajo</a>
  			</div>
		</div>

		<!-- Scripts -->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
		<script>
		// Función de ejemplo para exportar a PDF
			function exportarPDF(){
    			// En este punto podrías integrar jsPDF, por ejemplo:
    			// var doc = new jsPDF();
    			// doc.text("Detalle de la Orden", 10, 10);
    			// doc.save("Orden_<?= htmlspecialchars($orden['numero_ot']); ?>.pdf");
    			alert("Funcionalidad de exportación a PDF no implementada.");
				}
		</script>
	</body>
</html>