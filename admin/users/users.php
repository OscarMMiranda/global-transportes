<?php
// /admin/users/users.php

session_start();
require_once '../../includes/config.php';
$conn = getConnection();

// Depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');

// Validar acceso
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    error_log("❌ Acceso no autorizado: " . $_SERVER['REMOTE_ADDR']);
    header("Location: /login.php");
    exit();
}

// Helpers
require_once INCLUDES_PATH . '/helpers.php';
require_once INCLUDES_PATH . '/funciones.php';

// Registrar acceso
$stmt_hist = $conn->prepare("
    INSERT INTO historial_bd (usuario, accion, ip_usuario) 
    VALUES (?, '[VISTA] Usuarios Activos', ?)
");
if ($stmt_hist) {
    $stmt_hist->bind_param("ss", $_SESSION['usuario'], $_SERVER['REMOTE_ADDR']);
    $stmt_hist->execute();
    $stmt_hist->close();
}

// Exportar CSV
if (isset($_GET['exportar']) && $_GET['exportar'] === 'csv') {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="usuarios_activos.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID','Nombre','Apellido','Usuario','Correo','Rol','Fecha Creación']);

    $sql = "
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
    $result_csv = $conn->query($sql);
    while ($row = $result_csv->fetch_assoc()) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}

// Cargar HTML modular
require_once __DIR__ . '/partials/encabezado.php';
require_once __DIR__ . '/partials/navegacion.php';
?>

<div class="container-fluid">

  <!-- Encabezado y botón crear -->
  	

  <!-- Tabla única para activos y eliminados -->
  <div class="table-responsive">
    <table id="tablaUsuarios" class="table table-striped table-hover mb-0">


      <thead class="table-primary">
        <!-- <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Usuario</th>
          <th>Correo</th>
          <th>Rol</th>
          <th>Fecha Creación</th>
          <th class="text-center">Acciones</th>
        </tr> -->
      </thead>
      <tbody id="contenidoTabla">
        <tr><td colspan="8" class="text-center text-muted">Cargando usuarios...</td></tr>
      </tbody>
    </table>
  </div>

  <!-- Contenedor para modales dinámicos -->
  <div id="contenedorModales"></div>
</div>

<?php require_once __DIR__ . '/partials/scripts_ajax.php'; ?>