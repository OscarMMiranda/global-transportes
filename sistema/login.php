<?php
	// 1. Iniciar sesión y configurar entorno
	session_start();


	// Sólo mostramos errores en local
	if ($_SERVER['SERVER_NAME'] === 'localhost') {
    	ini_set('display_errors', 1);
    	ini_set('display_startup_errors', 1);
    	error_reporting(E_ALL);
		}

// // Conexión a la base de datos //
// include '../includes/conexion.php';



	// 4. Conectar a la base de datos (ruta absoluta)
	require_once __DIR__ . '/../includes/conexion.php';
	$error = '';


	// 5. Procesar POST
	if ($_SERVER['REQUEST_METHOD'] === 'POST') 
		{
    	if (!$conn) 
			{
        	die("Error de conexión con la base de datos.");
    		}

		// 5.2 Sanitizar entrada
        $usuario = trim($_POST['usuario']);
        $clave   = $_POST['clave'];


    	$stmt = $conn->prepare("
        	SELECT u.id, u.usuario, u.contrasena AS clave, u.rol AS rol_id, r.nombre AS rol_nombre
        	FROM usuarios u
        	JOIN roles r ON u.rol = r.id
        	WHERE u.usuario = ?
    		");

    	if (!$stmt) {
        	die("Error en la preparación de la consulta: " . $conn->error);
    		}

    	$stmt->bind_param("s", $usuario);
    	$stmt->execute();
    	$resultado = $stmt->get_result();

    	if ($resultado->num_rows === 1) {
        	$fila = $resultado->fetch_assoc();
        	if (password_verify($clave, $fila['clave'])) {
            	// Inicio de sesión exitoso
            	$_SESSION['usuario'] 	= $fila['usuario'];
            	$_SESSION['id'] 		= $fila['id'];
            	$_SESSION['rol'] 		= $fila['rol_id'];
            	$_SESSION['rol_nombre'] = $fila['rol_nombre'];

            	// Verifica que el archivo de destino exista
            	$ruta = '';
            	switch ($fila['rol_nombre']) 
					{
            	    case 'admin':
                	    $ruta = 'panel_admin.php';
                    	break;
                	case 'chofer':
                	    $ruta = 'panel_chofer.php';
                	    break;
                	case 'cliente':
                	    $ruta = 'panel_cliente.php';
                	    break;
                	case 'empleado':
                	    $ruta = 'panel_empleado.php';
                	    break;
                	default:
                	    $ruta = 'panel.php';
                	    break;
            		}

            // Verifica si el archivo existe antes de redirigir
            if (file_exists($ruta)) {
                header("Location: $ruta");
                exit();
            } else {
                $error = "Panel de destino no encontrado: $ruta";
            }

        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Acceso al Sistema - Global Transportes</title>

        <!-- FontAwesome para íconos -->
        <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        rel="stylesheet"
        />

        <!-- CSS personalizado -->
        <link rel="stylesheet" href="../css/login.css">
    </head>

    <body>
        <div class="login-wrapper">
            <div class="login-card">
                
                <!-- CABECERA con logo y nombre -->
                <div class="login-card__header">
                    <img src="../img/logo.png" alt="Logo" class="login-card__logo">
                    <h1 class="login-card__title">M I GLOBAL TRANSPORTES</h1>
                </div>
        
		        <!-- <header> -->
    		        <div class="login-card__body">
                        <h2>Acceso al Sistema</h2>
    		        </div>

  		        <!-- </header> -->
                <main class="contenido">
                    <div class="login-form">

                        <?php if ($error): ?>
                            <div class="login-card__error">
                                <i class="fa fa-exclamation-triangle"></i>
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>
        
                        <form action="login.php" method="post" class="formulario">
        
                            <!-- CSRF token -->
                            <input 
								type="hidden" 
								name="csrf_token" 
								value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                            <!-- formulario para el campo de usuario -->
		                    <div class="login-form__group">
        	                    <label for="usuario"><i class="fa fa-user"></i></label>
                                <input
            	                    type="text"
              	                    id="usuario"
              	                    name="usuario"
              	                    placeholder="Usuario"
              	                    required autofocus
                                >
                            </div>

                            <div class="login-form__group">
                                <label for="clave"><i class="fa fa-lock"></i></label>
                                <input
                                    type="password"
                                    id="clave"
                                    name="clave"
                                    placeholder="Contraseña"
                                    required
                                >
                            </div>
							
							<button type="submit" class="login-form__btn">Ingresar</button>
		                   
                            <div class="text-center my-3">
              					<button type="button" onclick="window.history.back()" class="btn btn-secondary">
                					⬅ Volver
    							</button>
							</div>
                        </form>
                    </div>
                </main>

                <footer>
                    <div class="login-card__footer">
                        &copy; 2025 Global Transportes. Todos los derechos reservados.
                    </div>
                </footer>
            </div>
        </div>

<script>
    setTimeout(function() {
        window.history.back();
    }, 15000); // Redirige después de 15 segundos
</script>


    </body>
</html>
