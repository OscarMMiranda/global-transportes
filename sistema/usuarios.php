<?php
	session_start();
	require_once '../includes/conexion.php';

	// 01. Activar modo depuración (quitar en producción)
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		ini_set('log_errors', 1);
		ini_set('error_log', 'error_log.txt');

	// 02. Verificar acceso solo para administradores
		if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') 
			{
    		error_log("❌ Acceso no autorizado: " . $_SERVER['REMOTE_ADDR']);
    		header("Location: login.php");
    		exit();
			}

	// Consulta para obtener usuarios y sus roles
	$sql = "SELECT u.id, u.nombre, u.apellido, u.usuario, u.correo, r.nombre AS rol, u.creado_en
        FROM usuarios u
        JOIN roles r ON u.rol = r.id
        ORDER BY u.id ASC";

	$resultado = $conn->query($sql);
	if (!$resultado) 
		{
    	die("<h3>❌ Error al obtener usuarios: " . $conn->error . "</h3>");
		}

	// **Registrar acceso en historial_bd** utilizando prepared statements
	$usuario = $_SESSION['usuario'];
	$accion = "Visualizó lista de usuarios";
	$ip_usuario = $_SERVER['REMOTE_ADDR'];
	$stmt_historial = $conn->prepare("INSERT INTO historial_bd (usuario, accion, ip_usuario) VALUES (?, ?, ?)");
	if ($stmt_historial) 
		{
    	$stmt_historial->bind_param("sss", $usuario, $accion, $ip_usuario);
    	$stmt_historial->execute();
    	$stmt_historial->close();
		} 
	else 
		{
    	error_log("❌ Error al preparar historial: " . $conn->error);
		}

	// Exportación a CSV
	if (isset($_GET['exportar']) && $_GET['exportar'] === 'csv') 
		{
    	// Asegurarnos de que no haya salida previa a los headers
    	header('Content-Type: text/csv');
    	header('Content-Disposition: attachment; filename="usuarios.csv"');

    	$output = fopen('php://output', 'w');
    	fputcsv($output, ["ID", "Nombre", "Apellido", "Usuario", "Correo", "Rol", "Fecha Creación"]);

    	// Ejecutar nuevamente la consulta para exportar datos
    	$result_csv = $conn->query($sql);
    	if ($result_csv) 
			{
        	while ($fila = $result_csv->fetch_assoc()) 
				{
            	fputcsv($output, $fila);
        		}
    		} 
		else 
			{
        	die("Error al obtener usuarios: " . $conn->error);
    		}
    	fclose($output);
    	exit();
	}
?>

<!DOCTYPE html>
	<html lang="es">

	<head>
		<meta charset="UTF-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
  		<title>Usuarios – Global Transportes</title>
  		<!-- Bootstrap 5 -->
  		<link 
    	href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
    	rel="stylesheet"/>
  		<!-- Font Awesome -->
  		<link 
    		href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
    		rel="stylesheet"/>
  		<!-- DataTables Bootstrap5 CSS -->
  		<link 
    		href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" 
    		rel="stylesheet"/>
  		<!-- Tu CSS -->
  			<link rel="stylesheet" href="../css/estilo.css"/>
	</head>


	<body class="bg-light">

		<!-- NAVBAR -->
  		<!-- <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4"> -->

		<div class="container">
			<a class="navbar-brand d-flex align-items-center" href="panel_admin.php">
        		<img src="../img/logo.png" width="100" class="me-2" alt="Logo">
        		Global Transportes
      		</a>

			<button 
        		class="navbar-toggler" 
        		type="button" 
        		data-bs-toggle="collapse" 
        		data-bs-target="#navbarUsuarios" 
        		aria-controls="navbarUsuarios" 
        		aria-expanded="false" 
        		aria-label="Mostrar navegación"
      		>
        		<span class="navbar-toggler-icon"></span>
      		</button>

			<div class="collapse navbar-collapse" id="navbarUsuarios">
        		<ul class="navbar-nav ms-auto">
        			<li class="nav-item"><a class="nav-link" href="panel_admin.php">Panel Admin</a></li>
          			<li class="nav-item"><a class="nav-link" href="crear_usuario.php">Crear Usuario</a></li>
          			<li class="nav-item"><a class="nav-link" href="?exportar=csv">Exportar CSV</a></li>
          			<li class="nav-item"><a class="nav-link" href="logout.php">Cerrar Sesión</a></li>
        		</ul>
      		</div>


    		<h1 class="mb-4">Lista de Usuarios</h1>

    		<a href="crear_usuario.php" class="boton-accion">➕ Crear Usuario</a>
    		<a href="usuarios.php?exportar=csv" class="boton-accion">📥 Exportar CSV</a>

    		<?php if ($resultado->num_rows > 0): ?>
        	
			
			<table 
        		id="tablaUsuarios" 
        		class="table table-striped table-bordered table-hover mb-0"
        		style="width:100%;"
      		>
            	<thead class="table-primary">
                	<tr>
                    	<th>ID</th>
                    	<th>Nombre</th>
                    	<th>Apellido</th>
                    	<th>Usuario</th>
                    	<th>Correo</th>
                    	<th>Rol</th>
                    	<th>Fecha Creación</th>
                    	<th>Acciones</th>
                	</tr>
            	</thead>
            	<tbody>
			
			
            	<?php while ($fila = $resultado->fetch_assoc()) : ?>
                	<tr>
                    	<td><?= htmlspecialchars($fila['id']); ?></td>
                    	<td><?= htmlspecialchars($fila['nombre']); ?></td>
                    	<td><?= htmlspecialchars($fila['apellido']); ?></td>
                    	<td><?= htmlspecialchars($fila['usuario']); ?></td>
                    	<td><?= htmlspecialchars($fila['correo']); ?></td>
                    	<td><?= htmlspecialchars(ucfirst($fila['rol'])); ?></td>
                    	<td><?= htmlspecialchars($fila['creado_en']); ?></td>                   	
						<td class="acciones">
								<div class="btn-group" role="group" aria-label="Acciones"></div>
                        		<a 
                     				href="editar_usuario.php?id=<?= urlencode($fila['id']) ?>" 
                      				class="btn btn-sm btn-outline-primary" 
                      				title="Editar"
                    			>
                      				<i class="fa fa-pencil-alt"></i>
                    			</a> |
                        		<a 
                      				href="eliminar_usuario.php?id=<?= urlencode($fila['id']) ?>" 
                      				onclick="return confirm('⚠️ ¿Eliminar este usuario?');" 
                      				class="btn btn-sm btn-outline-danger" 
                      				title="Eliminar"
                    			>
                      				<i class="fa fa-trash"></i>
                    			</a>
                    		</div>
						</td>		
                	</tr>
            	<?php endwhile; ?>
        	</tbody>
    	</table>
    	<?php else: ?>
        	<p>📌 No hay usuarios registrados.</p>
    	<?php endif; ?>
    	<p>
			<a href="panel_admin.php" class="btn btn-outline-secondary">← Volver al Panel
			</a>
		</p>
	
	

	
	</div>

	<!-- FOOTER -->
  

	<!-- jQuery (necesario para DataTables) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<!-- DataTables JS -->
	<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
	<!-- (Opcional) Bootstrap JS Bundle -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script>
		$(document).ready(function()
			{
    		$('#tablaUsuarios').DataTable(
				{
        		"language": 
					{
        	    	"url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        			}
    			});
			});
	</script>
	<!-- </nav> -->
</body>
</html>
