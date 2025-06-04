<?php
	session_start();
	require_once '../includes/conexion.php';

	// Depuración (quita en producción)
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	// Solo admins
	if (empty($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') 
		{
    	header("Location: login.php"); exit;
		}

	// Validar ID
	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
	if ($id <= 0) die("❌ ID no válido.");

	// Obtener usuario
	$stmt = $conn->prepare("SELECT id,nombre,apellido,correo,rol FROM usuarios WHERE id = ?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$usuario = $stmt->get_result()->fetch_assoc();
	$stmt->close();
	if (!$usuario) die("❌ Usuario no encontrado.");

	// Procesar POST
	$mensaje = "";
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    	$nombre   = trim($_POST['nombre']);
    	$apellido = trim($_POST['apellido']);
    	$correo   = trim($_POST['correo']);
    	$rol      = intval($_POST['rol']);
    	// Actualizar
    	$u = $conn->prepare
			(
    		"UPDATE usuarios SET nombre=?, apellido=?, correo=?, rol=? WHERE id=?"
    		);
    	$u->bind_param("ssssi", $nombre, $apellido, $correo, $rol, $id);
    	if ($u->execute()) {
        	$mensaje = "✅ Usuario actualizado correctamente.";
        	// Historial
        	$acc = "Modificó usuario ID $id";
        	$ip  = $_SERVER['REMOTE_ADDR'];
        	$usr = $_SESSION['usuario'];
        	$conn->query("
          		INSERT INTO historial_bd
            	(usuario,accion,tabla_afectada,ip_usuario)
          		VALUES
            	('$usr','$acc','usuarios','$ip')
        	");
        	// Refrescar datos
        	$usuario['nombre']   = $nombre;
        	$usuario['apellido'] = $apellido;
        	$usuario['correo']   = $correo;
        	$usuario['rol']      = $rol;
    		} 
		else {
        	$mensaje = "❌ Error al actualizar: " . $conn->error;
    		}
    	$u->close();
		}

	// Roles para el select
	$roles = $conn->query("SELECT id,nombre FROM roles");
?>

<!DOCTYPE html>
	<html lang="es">
	<head>
  		<meta charset="UTF-8"/>
  		<meta name="viewport" content="width=device-width,initial-scale=1"/>
  		<title>Editar Usuario – Global Transportes</title>

  		<!-- Bootstrap 5 -->
  		<link 
    		href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
    		rel="stylesheet"/>
  		<!-- FontAwesome -->
  		<link 
    		href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
    		rel="stylesheet"/>
  		<!-- Tu CSS -->
  		<link rel="stylesheet" href="../css/estilo.css"/>
	</head>
	
	<body class="bg-light">
		
		<div class="container py-5">
  			<div class="row justify-content-center">
    			<div class="col-12 col-md-10 col-xl-9">
      			<!-- <div class="card shadow p-4"> -->
      				<h3 class="card-title text-center mb-4">Editar Usuario</h3>
					

      				<?php if ($mensaje): ?>
        				<div class="alert <?= strpos($mensaje,'✅')!==false?'alert-success':'alert-danger' ?>">
          					<i class="fa <?= strpos($mensaje,'✅')!==false?'fa-check-circle':'fa-exclamation-triangle' ?> me-2"></i>
          					<?= htmlspecialchars($mensaje) ?>
        				</div>
      				<?php endif; ?>

      				<form method="post" onsubmit="return confirmarActualizacion();">
        				
						<div class="row mb-3 align-items-center">
          					<label for="nombre" class="col-sm-4 col-form-label">Nombre</label>
          					<div class="col-sm-8">
            					<input 
              						type="text"
              						id="nombre"
              						name="nombre"
              						class="form-control"
              						value="<?= htmlspecialchars($usuario['nombre']) ?>"
              						required
            					/>
          					</div>
        				</div>

        				<div class="row mb-3 align-items-center">
          					<label for="apellido" class="col-sm-4 col-form-label">Apellido</label>
          					<div class="col-sm-8">
            					<input 
              						type="text"
              						id="apellido"
              						name="apellido"
              						class="form-control"
              						value="<?= htmlspecialchars($usuario['apellido']) ?>"
              						required
            					/>
          					</div>
        				</div>

        				<div class="row mb-3 align-items-center">
          					<label for="correo" class="col-sm-4 col-form-label">Correo</label>
          					<div class="col-sm-8">
            					<input 
              						type="email"
              						id="correo"
              						name="correo"
              						class="form-control"
              						value="<?= htmlspecialchars($usuario['correo']) ?>"
              						required
            					/>
          					</div>
        				</div>

   	     				<div class="row mb-4 align-items-center">
          					<label for="rol" class="col-sm-4 col-form-label">Rol</label>
          					<div class="col-sm-8">
            					<select 
              						id="rol" 
              						name="rol" 
              						class="form-select" 
              						required
            					>
              						<?php while ($r = $roles->fetch_assoc()): ?>
                					<option 
                  						value="<?= $r['id'] ?>" 
                  						<?= $usuario['rol']==$r['id']?'selected':'' ?>
                					>
                  						<?= ucfirst(htmlspecialchars($r['nombre'])) ?>
                					</option>
              						<?php endwhile; ?>
            					</select>
          					</div>
        				</div>

        				<div class="d-grid gap-2">
          					<button 
            					type="submit" 
            					class="btn btn-primary"
          					>
            					<i class="fa fa-save me-1"></i> Actualizar
          					</button>
          					<a 
            					href="usuarios.php" 
            					class="btn btn-outline-secondary"
          					>
            					<i class="fa fa-arrow-left me-1"></i> Volver a la lista
          					</a>
        				</div>
      				</form>
    			</div>
			
  			</div>
		</div>

		<script>
			function confirmarActualizacion() {
  			return confirm("⚠️ ¿Seguro que quieres actualizar este usuario?");
			}
		</script>
		<script 
  			src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
		</script>
	</body>
</html>
