<?php
// archivo: /admin/users/vistas/editar_usuario.php

session_start();
require_once __DIR__ . '/../../includes/config.php';
$conn = getConnection();

// Depuración (quitar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validar acceso
if (empty($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    header("Location: ../login.php");
    exit;
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

    $u = $conn->prepare("UPDATE usuarios SET nombre=?, apellido=?, correo=?, rol=? WHERE id=?");
    $u->bind_param("ssssi", $nombre, $apellido, $correo, $rol, $id);

    if ($u->execute()) {
        $mensaje = "✅ Usuario actualizado correctamente.";

        // Registrar historial
        $acc = "Modificó usuario ID $id desde vista tradicional";
        $ip  = $_SERVER['REMOTE_ADDR'];
        $usr = $_SESSION['usuario'];
        $conn->query("
            INSERT INTO historial_bd (usuario,accion,tabla_afectada,ip_usuario)
            VALUES ('$usr','$acc','usuarios','$ip')
        ");

        // Refrescar datos
        $usuario['nombre']   = $nombre;
        $usuario['apellido'] = $apellido;
        $usuario['correo']   = $correo;
        $usuario['rol']      = $rol;
    } else {
        $mensaje = "❌ Error al actualizar: " . $conn->error;
    }
    $u->close();
}

// Obtener roles
$roles = $conn->query("SELECT id,nombre FROM roles");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Editar Usuario – Global Transportes</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
  <!-- Tu CSS -->
   <link rel="stylesheet" href="/css/estilo.css">

</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-xl-8">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <h4 class="card-title text-center mb-4">
              <i class="fas fa-user-edit me-2 text-primary"></i> Editar Usuario
            </h4>

            <div class="alert alert-warning small">
              <i class="fas fa-exclamation-triangle me-2"></i>
              Estás editando desde la vista completa. Para edición rápida, usa el panel de usuarios.
            </div>

            <?php
              $modo = 'editar';
              require_once __DIR__ . '/../partials/form_usuario.php';
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function confirmarActualizacion() {
      return confirm("⚠️ ¿Seguro que quieres actualizar este usuario?");
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>