<?php
// /admin/users/usuarios_eliminados.php

session_start();

// 2) Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors',     1);
ini_set('error_log',      __DIR__ . '/error_log.txt');

// 3) Cargar config.php (define getConnection() y rutas)
require_once __DIR__ . '/../../includes/config.php';

// 4) Obtener la conexión
$conn = getConnection();

// 02. Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    error_log("❌ Acceso no autorizado a usuarios eliminados: " . $_SERVER['REMOTE_ADDR']);
    header("Location: ../login.php");
    exit();
}

// 03. Consulta para obtener usuarios eliminados
$sql_eliminados = "
    SELECT 
        u.id,
        u.nombre,
        u.apellido,
        u.usuario,
        u.correo,
        r.nombre AS rol,
        u.eliminado_en,
        u.eliminado_por
    FROM usuarios u
    JOIN roles r ON u.rol = r.id
    WHERE u.eliminado = 1
    ORDER BY u.eliminado_en DESC
";
$result_eliminados = $conn->query($sql_eliminados);
if (!$result_eliminados) {
    die("<h3>❌ Error al obtener usuarios eliminados: " . $conn->error . "</h3>");
}

// 04. Registrar acceso en historial_bd
$stmt_hist = $conn->prepare("
    INSERT INTO historial_bd (usuario, accion, ip_usuario) 
    VALUES (?, '[VISTA] Usuarios Eliminados', ?)
");
if ($stmt_hist) {
    $usuario_admin = $_SESSION['usuario'];
    $ip_usuario    = $_SERVER['REMOTE_ADDR'];
    $stmt_hist->bind_param("ss", $usuario_admin, $ip_usuario);
    $stmt_hist->execute();
    $stmt_hist->close();
} else {
    error_log("❌ Error al preparar historial (eliminados): " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Usuarios Eliminados – M&I Global Transportes</title>
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

  <div class="container my-4">
    <!-- Navegación entre Activos / Eliminados -->
    <nav class="mb-3">
      <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="nav-link" href="users.php">Activos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="usuarios_eliminados.php">Eliminados</a>
        </li>
      </ul>
    </nav>

    <h1 class="h3 text-danger mb-4">Usuarios Eliminados</h1>

    <?php if ($result_eliminados->num_rows > 0): ?>
      <div class="table-responsive">
        <table id="tablaEliminados" class="table table-striped table-hover mb-0">
          <thead class="table-danger">
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Apellido</th>
              <th>Usuario</th>
              <th>Correo</th>
              <th>Rol</th>
              <th>Eliminado En</th>
              <th>Eliminado Por</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($u = $result_eliminados->fetch_assoc()): ?>
              <tr class="table-danger">
                <td><?= htmlspecialchars($u['id']) ?></td>
                <td><?= htmlspecialchars($u['nombre']) ?></td>
                <td><?= htmlspecialchars($u['apellido']) ?></td>
                <td><?= htmlspecialchars($u['usuario']) ?></td>
                <td><?= htmlspecialchars($u['correo']) ?></td>
                <td><?= htmlspecialchars(ucfirst($u['rol'])) ?></td>
                <td><?= htmlspecialchars($u['eliminado_en']) ?></td>
                <td><?= htmlspecialchars($u['eliminado_por']) ?></td>
                <td class="text-center">
                  <a 
                    href="restaurar_usuario.php?id=<?= urlencode($u['id']) ?>" 
                    class="btn btn-sm btn-outline-success" 
                    title="Restaurar"
                    onclick="return confirm('¿Restaurar este usuario?');"
                  >
                    <i class="fa fa-undo"></i>
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="text-muted">No hay usuarios eliminados.</p>
    <?php endif; ?>

    <a href="users.php" class="btn btn-outline-secondary mt-4">
      ← Volver a Usuarios Activos
    </a>
  </div>

  <!-- jQuery (necesario para DataTables) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
  <!-- Bootstrap Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#tablaEliminados').DataTable({
        language: {
          url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        },
        paging: false,
        searching: false
      });
    });
  </script>
</body>
</html>
