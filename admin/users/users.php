<?php
// /admin/users/users.php

session_start();
require_once '../../includes/conexion.php';

// 01. Activar modo depuración (quitar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');

// 02. Cargar configuración global
require_once __DIR__ . '/../../includes/config.php';

// 03. Obtener la conexión
$conn = getConnection();



// 02. Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    error_log("❌ Acceso no autorizado: " . $_SERVER['REMOTE_ADDR']);
    header("Location: ../login.php");
    exit();
}

// 05. Cargar helpers y funciones
require_once INCLUDES_PATH . '/helpers.php';
require_once INCLUDES_PATH . '/funciones.php';

// 03. Consulta para obtener usuarios activos
$sql_activos = "
    SELECT 
        u.id,
        u.nombre,
        u.apellido,
        u.usuario,
        u.correo,
        r.nombre AS rol,
        u.creado_en
    FROM usuarios u
    JOIN roles r ON u.rol = r.id
    WHERE u.eliminado = 0
    ORDER BY u.id ASC
";
$result_activos = $conn->query($sql_activos);
if (!$result_activos) {
    die("<h3>❌ Error al obtener usuarios activos: " . $conn->error . "</h3>");
}

// 04. Registrar acceso en historial_bd
$stmt_hist = $conn->prepare("
    INSERT INTO historial_bd (usuario, accion, ip_usuario) 
    VALUES (?, '[VISTA] Usuarios Activos', ?)
");
if ($stmt_hist) {
    $usuario_admin = $_SESSION['usuario'];
    $ip_usuario    = $_SERVER['REMOTE_ADDR'];
    $stmt_hist->bind_param("ss", $usuario_admin, $ip_usuario);
    $stmt_hist->execute();
    $stmt_hist->close();
} else {
    error_log("❌ Error al preparar historial (activos): " . $conn->error);
}

// 05. Exportación a CSV
if (isset($_GET['exportar']) && $_GET['exportar'] === 'csv') {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="usuarios_activos.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID','Nombre','Apellido','Usuario','Correo','Rol','Fecha Creación']);

    $result_csv = $conn->query($sql_activos);
    if ($result_csv) {
        while ($row = $result_csv->fetch_assoc()) {
            fputcsv($output, $row);
        }
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
  	<title>Usuarios Activos – M&I Global Transportes</title>
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
    <!-- Navegación principal -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3">Usuarios Activos</h1>
      <div>
        <a href="../../paneles/panel_admin.php" class="btn btn-outline-secondary">
          <i class="fa fa-arrow-left"></i> Panel Admin
        </a>
      </div>
    </div>

    <!-- Tabs Activos / Eliminados -->
    <nav class="mb-4">
      <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="nav-link active" href="users.php">Activos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="usuarios_eliminados.php">Eliminados</a>
        </li>
      </ul>
    </nav>

    <!-- Botones de acción -->
    <div class="mb-3">
      <a href="crear_usuario.php" class="btn btn-primary me-2">
        <i class="fa fa-plus"></i> Crear Usuario
      </a>
      <a href="users.php?exportar=csv" class="btn btn-success">
        <i class="fa fa-file-csv"></i> Exportar CSV
      </a>
    </div>

    <!-- Tabla de usuarios activos -->
    <?php if ($result_activos->num_rows > 0): ?>
      <div class="table-responsive">
        <table id="tablaActivos" class="table table-striped table-hover mb-0">
          <thead class="table-primary">
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Apellido</th>
              <th>Usuario</th>
              <th>Correo</th>
              <th>Rol</th>
              <th>Fecha Creación</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($u = $result_activos->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($u['id']) ?></td>
                <td><?= htmlspecialchars($u['nombre']) ?></td>
                <td><?= htmlspecialchars($u['apellido']) ?></td>
                <td><?= htmlspecialchars($u['usuario']) ?></td>
                <td><?= htmlspecialchars($u['correo']) ?></td>
                <td><?= htmlspecialchars(ucfirst($u['rol'])) ?></td>
                <td><?= htmlspecialchars($u['creado_en']) ?></td>
                <td class="text-center">
                  <a href="editar_usuario.php?id=<?= urlencode($u['id']) ?>"
                     class="btn btn-sm btn-outline-primary" title="Editar">
                    <i class="fa fa-pencil-alt"></i>
                  </a>
                  <a href="eliminar_usuario.php?id=<?= urlencode($u['id']) ?>"
                     onclick="return confirm('⚠️ ¿Eliminar este usuario?');"
                     class="btn btn-sm btn-outline-danger" title="Eliminar">
                    <i class="fa fa-trash"></i>
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="text-muted">No hay usuarios activos.</p>
    <?php endif; ?>

  </div>

  <!-- Scripts JS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#tablaActivos').DataTable({
        language: {
          url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        }
      });
    });
  </script>
</body>
</html>
