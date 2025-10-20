<?php
// archivo: acciones/crear.php
// propósito: crear una nueva relación zona-distrito

require_once __DIR__ . '/../controllers/zonas_controller.php';

if (!isset($_POST['zona_id'], $_POST['distrito_id'])) {
  $_SESSION['error'] = '❌ Datos incompletos.';
  header('Location: index.php');
  exit;
}

$post = [
  'id'          => 0, // fuerza modo creación
  'zona_id'     => $_POST['zona_id'],
  'distrito_id' => $_POST['distrito_id']
];

$error = procesarDistrito($post);

if ($error) {
  $_SESSION['error'] = $error;
  header('Location: index.php');
  exit;
}

// ✅ Creación exitosa
header('Location: index.php?msg=Registro creado correctamente');
exit;