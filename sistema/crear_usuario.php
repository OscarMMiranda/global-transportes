<?php
session_start();
include '../includes/conexion.php';

// Solo admins pueden acceder
if (!isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$error = "";
$exito = "";

// Obtener roles disponibles desde la tabla 'roles'
$roles_result = $conn->query("SELECT id, nombre FROM roles");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $usuario = trim($_POST['usuario']);
    $correo = trim($_POST['correo']);
    $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
    $rol = (int) $_POST['rol'];

    // Validar duplicados
    $verificar = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ? OR correo = ?");
    $verificar->bind_param("ss", $usuario, $correo);
    $verificar->execute();
    $verificar->store_result();

    if ($verificar->num_rows > 0) {
        $error = "❌ El nombre de usuario o correo ya existe.";
    } else {
        $sql = $conn->prepare("INSERT INTO usuarios (nombre, apellido, usuario, correo, contrasena, rol) VALUES (?, ?, ?, ?, ?, ?)");
        $sql->bind_param("sssssi", $nombre, $apellido, $usuario, $correo, $clave, $rol);

        if ($sql->execute()) {
            $exito = "✅ Usuario creado correctamente.";
        } else {
            $error = "❌ Error al crear el usuario.";
        }

        $sql->close();
    }

    $verificar->close();
}
?>

<!DOCTYPE html>
	<html lang="es">
	<head>
    	<meta charset="UTF-8">
    	<meta name="viewport" content="width=device-width,initial-scale=1">
    	<title>Crear Usuario</title>

    	<!-- Bootstrap 5 -->
    	<link 
    		href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
      		rel="stylesheet"/>

    	<!-- Font Awesome -->
    	<link 
      		href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
      		rel="stylesheet"/>

    	<!-- Tu CSS personalizado -->
    	<link rel="stylesheet" href="../css/estilo.css"/>
	</head>


	<body class="bg-light">
		<div class="container py-1">
			
			<div class="card mx-auto shadow-lg p-4" style="width: 80%; max-width: 800px; border-radius: 1.5rem; margin-top: -60px;">

    			<h1 class="text-center fs-3 mb-3 mt-3 fw-bold fst-italic">Crear Usuario</h1>


    			<?php if ($error): ?>
        			<div class="mensaje-sistema" style="background-color: #fee; color: #a00; padding: 10px;">
            			<?= htmlspecialchars($error) ?>
        			</div>
    				<?php elseif ($exito): ?>
        			<div class="mensaje-sistema" style="background-color: #e6ffed; color: #007700; padding: 10px;">
            			<?= htmlspecialchars($exito) ?>
        			</div>
    			<?php endif; ?>

    			<form action="crear_usuario.php" method="POST" class="login-form">
					<div class="formulario">
            			
						<div class="row mb-3 align-items-center">
          					<label for="nombre" class="col-sm-4 col-form-label">Nombre</label>
          					<div class="col-sm-8">
            					<input 
              						type="text" 
              						class="form-control" 
              						id="nombre" 
              						name="nombre" 
              						required
									autofocus
            					/>
          					</div>
        				</div>

   	  		    		<div class="row mb-3 align-items-center">
          					<label for="apellido" class="col-sm-4 col-form-label">Apellido</label>
          					<div class="col-sm-8">
            					<input 
              						type="text" 
              						class="form-control" 
              						id="apellido" 
              						name="apellido" 
              						required
            					/>
          					</div>
        				</div>
            	
						<div class="row mb-3 align-items-center">
          					<label for="usuario" class="col-sm-4 col-form-label">
            					Nombre de usuario
          					</label>
          					<div class="col-sm-8">
            					<input 
              						type="text" 
              						class="form-control" 
              						id="usuario" 
              						name="usuario" 
              						required
            					/>
          					</div>
        				</div>
				
						<div class="row mb-3 align-items-center">
          					<label for="correo" class="col-sm-4 col-form-label">
            					Correo electrónico
          					</label>
          					<div class="col-sm-8">
            					<input 
              						type="email" 
              						class="form-control" 
              						id="correo" 
              						name="correo" 
              						required
            					/>
          					</div>
        				</div>
				
						<div class="row mb-3 align-items-center">
        					<label for="clave" class="col-sm-4 col-form-label">Contraseña</label>
        					<div class="col-sm-8">
            					<input 
            						type="password" 
              						class="form-control" 
              						id="clave" 
              						name="clave" 
              						required
            					/>
          					</div>
        				</div>
            	
						<div class="row mb-4 align-items-center">
          					<label for="rol" class="col-sm-4 col-form-label">Rol</label>
          					<div class="col-sm-8">
            					<select 
              						class="form-select" 
              						id="rol" 
              						name="rol" 
              						required
            					>
              						<option value="">-- Selecciona un rol --</option>
              						<?php while ($row = $roles_result->fetch_assoc()): ?>
                					<option 
                  						value="<?= $row['id'] ?>"
                					>
                  						<?= ucfirst(htmlspecialchars($row['nombre'])) ?>
                					</option>
              						<?php endwhile; ?>
            					</select>
          					</div>
        				</div>
            	
						<button type="submit" class="btn btn-primary w-100">Crear Usuario</button>
        			</div>
    			</form>

				<div class="d-flex justify-content-end mt-3">
  <a href="usuarios.php" class="btn btn-outline-secondary">
    <i class="fa fa-arrow-left me-1"></i> Volver a la lista
  </a>

  <footer class="bg-white text-center py-3 mt-auto">
    	<div class="container">
    		<small class="text-muted">&copy; 2025 Global Transportes. Todos los derechos reservados.</small>
    	</div>
  	</footer>
</div>
			</div>
    
		</div>

		<!-- Bootstrap JS -->
		<script 
  			src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
		</script>
	</body>
</html>

