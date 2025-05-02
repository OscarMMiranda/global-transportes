<?php
session_start();
include('conexion.php');

// Verificamos si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Buscar usuario en la base
    $sql = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $fila = $resultado->fetch_assoc();
        
        // Verificar contraseña con hash
        if (password_verify($contrasena, $fila['contrasena'])) {
            $_SESSION['usuario'] = $fila['usuario'];
            $_SESSION['rol'] = $fila['rol'];
            header("Location: dashboard.php"); // Redirige al sistema
            exit;
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>

<!-- HTML del formulario de login -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Acceso al Sistema</title>
  <link rel="stylesheet" href="../css/">
</head>
<body>
  <section class="login-form">
    <h2>Acceso al Sistema</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form class="formulario" method="POST" action="">
      <input type="text" name="usuario" placeholder="Usuario" required>
      <input type="password" name="contrasena" placeholder="Contraseña" required>
      <button type="submit">Ingresar</button>
    </form>
  </section>
</body>
</html>
