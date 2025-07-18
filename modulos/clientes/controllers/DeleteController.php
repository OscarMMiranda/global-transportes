<?php
// modulos/clientes/controllers/DeleteController.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1) Carga config y modelo
require_once rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/includes/config.php';
require_once rtrim($_SERVER['DOCUMENT_ROOT'], '/\\')
          . '/modulos/clientes/models/Cliente.php';
Cliente::init($conn);

// 2) Validar y ejecutar borrado lógico
if (! isset($_GET['id']) || ! ctype_digit($_GET['id'])) {
    header('Location: index.php?action=list&msg=error');
    exit;
}

$id  = (int) $_GET['id'];
$ok  = Cliente::delete($id);
$msg = $ok ? 'ok' : 'error';

// 3) Redirigir a la lista con feedback
header('Location: index.php?action=list&msg=' . $msg);
exit;
