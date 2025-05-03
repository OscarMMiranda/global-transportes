<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../includes/conexion.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!$conn) {
        die("Error de conexión con la base de datos.");
    }

    $usuario = trim($_POST['usuario']);
    $clave = $_POST['clave'];

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
            $_SESSION['usuario'] = $fila['usuario'];
            $_SESSION['id'] = $fila['id'];
            $_SESSION['rol'] = $fila['rol_id'];
            $_SESSION['rol_nombre'] = $fila['rol_nombre'];

            // Verifica que el archivo de destino exista
            $ruta = '';
            switch ($fila['rol_nombre']) {
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
  <title>Acceso al Sistema - Global Transportes</title>
  <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
  <header>
    <div class="contenedor">
      <div class="logo">
        <a href="../index.html"><img src="../img/logo.png" alt="Logo Global Transportes" class="logo-img"></a>
      </div>
      <h1>Acceso al Sistema</h1>
    </div>
  </header>

  <main class="contenido">
    <div class="login-form">
      <h2>Iniciar Sesión</h2>

      <?php if ($error): ?>
        <p style="color: red; font-weight: bold;"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form action="login.php" method="post" class="formulario">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="clave" placeholder="Contraseña" required>
        <button type="submit">Ingresar</button>
      </form>
    </div>
  </main>

  <footer>
    <p>&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
  </footer>
</body>
</html>
