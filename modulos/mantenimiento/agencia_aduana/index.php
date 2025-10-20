<?php
// archivo: /modulos/mantenimiento/agencia_aduana/index.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/controllers/agencias_controller.php';

$conn = getConnection();
if (!$conn) {
    die("❌ Error en la conexión: " . mysqli_connect_error());
}

if (empty($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    header('Location: ../../login.php');
    exit;
}

// 🔀 Enrutador principal
$action = isset($_GET['action']) ? $_GET['action'] : 'view';
$error  = '';
$registro = [];

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Agencias Aduanas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- ✅ Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- ✅ FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <!-- ✅ DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>
<body class="bg-light">

<div class="container py-4">
<?php
switch ($action) {
    case 'store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $error = procesarFormulario($_POST);
        }
        // No break: continúa con 'view'

    case 'view':
        $registro      = agenciaVacia();
        $departamentos = listarDepartamentos();
        $provincias    = listarProvincias();
        $distritos     = listarDistritos();
        $agencias      = listarAgencias();
        include __DIR__ . '/vistas/view.php';
        break;

    case 'edit':
        $registro      = obtenerRegistro((int)$_GET['id']);
        $departamentos = listarDepartamentos();
        $provincias    = listarProvincias();
        $distritos     = listarDistritos();
        $agencias      = listarAgencias();
        include __DIR__ . '/vistas/form_edit.php';
        break;

    case 'delete':
        eliminarAgencia((int)$_GET['id']);
        header('Location: index.php?msg=eliminado');
        break;

    case 'reactivate':
        reactivarAgencia((int)$_GET['id']);
        header('Location: index.php?msg=reactivado');
        break;

    default:
        echo '<div class="alert alert-danger text-center">❌ Acción no reconocida.</div>';
        break;
}
?>
</div>

<!-- ✅ jQuery + Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- ✅ DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- ✅ JS del módulo -->
<script src="/modulos/mantenimiento/agencia_aduana/js/agencia_aduanas.js"></script>

</body>
</html>
<?php
$conn->close();
?>