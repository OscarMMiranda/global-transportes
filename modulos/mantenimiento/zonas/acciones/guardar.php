<?php
// archivo: acciones/guardar.php
// propósito: procesar formulario de ruta entre distritos

session_start();

if (!isset($_POST['zona_id'], $_POST['origen_id'], $_POST['destino_id'])) {
  $_SESSION['error'] = '❌ Datos incompletos.';
  header('Location: index.php');
  exit;
}

require_once __DIR__ . '/../controllers/zonas_controller.php';

$error = procesarRuta($_POST); // ← usa la función correcta

if ($error) {
  $_SESSION['error'] = $error;
  header('Location: index.php?id=' . (int)$_POST['id']);
  exit;
}

// ✅ Guardado exitoso
header('Location: index.php?msg=Guardado correctamente');
exit;