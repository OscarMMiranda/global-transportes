<?php
	// archivo: /modulos/mantenimiento/agencia_aduana/ajax/lista_activos.php




	session_start();

	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	ini_set('error_log', __DIR__ . '/error_log.txt');

	register_shutdown_function(function() {
  		$error = error_get_last();
  		if ($error) {
    		echo '<div class="alert alert-danger text-center">❌ Error fatal: ' . $error['message'] . '</div>';
    		error_log("❌ Error fatal: " . $error['message']);
  			}
		});

	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';
	require_once __DIR__ . '/../modelo/AgenciaModel.php';

	$conexion = getConnection();
	if (!$conexion || !$conexion instanceof mysqli) {
  		echo '<div class="alert alert-danger text-center">❌ Conexión inválida.</div>';
  		error_log("❌ Conexión inválida o no es mysqli.");
  		return;
		}

	$modelo = new AgenciaModel($conexion);
	$agencias = $modelo->listarActivas();

	if (!is_array($agencias)) {
  		echo '<div class="alert alert-danger text-center">❌ Error al obtener agencias activas.</div>';
  		error_log("❌ El modelo no devolvió un array válido.");
  		return;
		}

	if (empty($agencias)) {
  		echo '<div class="alert alert-info text-center">🟢 No hay agencias activas registradas.</div>';
  		error_log("ℹ️ No se encontraron agencias activas.");
  		return;
		}

	// 🔧 Función para renderizar botones
	function renderBotones($id) {
  		return '
    	<button class="btn btn-info btn-sm btn-accion me-1" onclick="verAgencia(' . $id . ')" title="Ver detalles">
      		<i class="fas fa-eye"></i> Ver
    	</button>
    	<button class="btn btn-warning btn-sm btn-accion me-1" onclick="abrirModalEditar(' . $id . ')" title="Editar agencia">
      		<i class="fas fa-edit"></i> Editar
    	</button>
    	<button class="btn btn-danger btn-sm btn-accion" onclick="eliminarAgencia(' . $id . ')" title="Eliminar agencia">
      		<i class="fas fa-trash-alt"></i> Eliminar
    	</button>
  		';
		}

	echo '<table id="tablaActivos" class="table table-bordered table-hover table-sm">
  			<thead class="table-light">
    			<tr>
      				<th>Nombre</th>
      				<th>RUC</th>
      				<th>Dirección</th>
      				<th>Creación</th>
      				<th>Acciones</th>
    			</tr>
  			</thead>
  		
		<tbody>';
			foreach ($agencias as $agencia) {
    		echo '<tr>
  					<td>' . htmlspecialchars(isset($agencia['nombre']) ? $agencia['nombre'] : '—') . '</td>
  					<td>' . htmlspecialchars(isset($agencia['ruc']) ? $agencia['ruc'] : '—') . '</td>
  					<td>' . htmlspecialchars(isset($agencia['direccion']) ? $agencia['direccion'] : '—') . '</td>
  					<td>' . htmlspecialchars(isset($agencia['fecha_creacion']) ? $agencia['fecha_creacion'] : '—') . '</td>
  					<td>' . renderBotones($agencia['id']) . '</td>
				</tr>';
				}
	echo '</tbody></table>';


	