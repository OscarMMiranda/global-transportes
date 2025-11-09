<?php
// archivo: /modulos/mantenimiento/zonas/index.php

session_start();

// 🛠️ Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// 🔗 Conexión y controlador
require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();
require_once __DIR__ . '/controllers/zonas_controller.php';

// 🔐 Validación de sesión y rol
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
  header('Location: ../../../login.php');
  exit;
}

// ⚙️ Acciones modularizadas
if (isset($_GET['eliminar'])) {
  include __DIR__ . '/acciones/eliminar.php';
  exit;
}
if (isset($_GET['activar'])) {
  include __DIR__ . '/acciones/activar.php';
  exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  include __DIR__ . '/acciones/guardar.php';
  exit;
}

// 📦 Datos para vista
require_once __DIR__ . '/helpers/datos_vista.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Distritos por Zona</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

  <!-- 🧩 Encabezado -->
  <?php include __DIR__ . '/componentes/encabezado.php'; ?>

  <!-- 🧩 Mensajes -->
  <?php include __DIR__ . '/componentes/mensajes_flash.php'; ?>

  <!-- 🧩 Pestañas de vista -->
  <?php include __DIR__ . '/componentes/tabs_estado.php'; ?>
  
  <!-- 🧩 Tabla de subzonas -->
  <?php
    extract([
      'subzonas' => $subzonas,
      'verEliminadas' => $verEliminadas
    ]);
    include __DIR__ . '/componentes/tabla_subzonas.php';
  ?>

  <!-- 🧩 Modales -->
  <?php include __DIR__ . '/modales/modal_agregar.php'; ?>
  <?php include __DIR__ . '/modales/modal_confirmar.php'; ?>
  <?php include __DIR__ . '/modales/modal_editar.php'; ?>

  <!-- 🧩 Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <?php if ($registro['id'] > 0): ?>
  <script>
    new bootstrap.Modal(document.getElementById('modalZona')).show();
  </script>
  <?php endif; ?>
</body>
</html>