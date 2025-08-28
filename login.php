<?php
// archivo	:	/login.php


// 	1. 	Mostrar errores para depuración
ini_set('display_errors',    1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


//	2.	Cargar la configuración global
require_once __DIR__ . '/includes/config.php';

// 	3. 	(Opcional) Si config.php no arranca la sesión, la iniciamos aquí
if (session_status() === PHP_SESSION_NONE) {
    session_start();
	}

// 	4. 	Obtener la conexión
$conn = getConnection();

// $_SESSION['usuario_id'] = $usuario['id'];

//	5.	Generar o recuperar token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

// 	6. 	Variables iniciales
$error   = '';
$usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
$clave    = isset($_POST['clave'])   ? $_POST['clave']          : '';

// 	7. 	Procesar envío
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 	7.1 	Validar CSRF
    if (! isset($_POST['csrf_token']) || 
         ! hash_equals($csrf, $_POST['csrf_token'])
    ) {
        $error = 'Token de seguridad inválido.';
    }
    // 	7.2 	Validar campos
    elseif ($usuario === '' || $clave === '') {
        $error = 'Usuario y contraseña son requeridos.';
    	}
    else {
        // 	7.3 	Preparar y ejecutar consulta
        $sql = "
            SELECT 
                u.id,
                u.usuario,
                u.contrasena    AS clave_hash,
                u.rol           AS rol_id,
                r.nombre        AS rol_nombre
            FROM usuarios u
            JOIN roles r ON u.rol = r.id
            WHERE u.usuario = ?
            LIMIT 1
        ";
        $stmt = $conn->prepare($sql);
        if (! $stmt) {
            die('Error interno: ' . $conn->error);
        }
        $stmt->bind_param('s', $usuario);
        $stmt->execute();


		// En PHP 5.6, asegúrate de usar mysqlnd para get_result()
        $res = $stmt->get_result();

        // 5.4 Verificar existencia de usuario
        if ($res && $res->num_rows === 1) {
            $fila = $res->fetch_assoc();

            // 	7.4 	Verificar contraseña
            if (password_verify($clave, $fila['clave_hash'])) {
                
				// 7.5 Guardar datos en sesión
                session_regenerate_id(true);
                $_SESSION['id']           = $fila['id'];
                $_SESSION['usuario_id']   = $fila['id']; // ← Esta es la que necesita el controlador

                $_SESSION['usuario']      = $fila['usuario'];
                $_SESSION['rol']          = $fila['rol_id'];
                $_SESSION['rol_nombre']   = $fila['rol_nombre'];

                $_SESSION['login_time']   = date('Y-m-d H:i:s');
                $_SESSION['ip_origen']    = $_SERVER['REMOTE_ADDR'];


                // 7.6 Redireccionar según rol
                switch ($fila['rol_nombre']) {
                    case 'admin':
                        $dest = '/paneles/panel_admin.php'; break;
                    case 'chofer':
                        $dest = '/paneles/panel_chofer.php'; break;
                    case 'cliente':
                        $dest = '/paneles/panel_cliente.php'; break;
                    case 'empleado':
                        $dest = '/paneles/panel_empleado.php'; break;
                    default:
                        $dest = '/paneles/panel.php';
                }





                // Verificar existencia y redirigir
                if (file_exists(__DIR__ . $dest)) {
                    header("Location: $dest");
                    exit;
                }
                $error = "Panel de destino no encontrado: $dest";
            }
            else {
                $error = 'Contraseña incorrecta.';
            }
        }
        else {
            $error = 'Usuario no encontrado.';
        }
        $stmt->close();
    }
}

// 6. Cerrar conexión
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Acceso al Sistema – Global Transportes</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height:100vh; background:linear-gradient(135deg,#0052D4,#4364F7);">

  <div class="card p-4" style="width:100%; max-width:400px;">
    <h4 class="text-center mb-4">Global Transportes</h4>

    <?php if ($error): ?>
      <div class="alert alert-danger">
        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
      </div>
    <?php endif; ?>

    <form method="post" action="">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">

      <div class="mb-3">
        <label for="usuario" class="form-label">Usuario</label>
        <input 
          type="text" 
          id="usuario" 
          name="usuario" 
          class="form-control" 
          value="<?= htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8') ?>" 
          required
        >
      </div>

      <div class="mb-3">
        <label for="clave" class="form-label">Contraseña</label>
        <input 
          type="password" 
          id="clave" 
          name="clave" 
          class="form-control" 
          required
        >
      </div>

      <button type="submit" class="btn btn-primary w-100">Ingresar</button>
    </form>
  </div>
</body>
</html>
