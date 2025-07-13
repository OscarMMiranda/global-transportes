<?php
// guardar_clientes.php (PHP 5.6 + Bootstrap 5)

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../../includes/conexion.php';

// 1) Capturar y sanear datos
$nombre         = isset($_POST['nombre'])         ? trim($_POST['nombre'])         : '';
$ruc            = isset($_POST['ruc'])            ? trim($_POST['ruc'])            : '';
$direccion      = isset($_POST['direccion'])      ? trim($_POST['direccion'])      : '';
$telefono       = isset($_POST['telefono'])       ? trim($_POST['telefono'])       : '';
$correo         = isset($_POST['correo'])         ? trim($_POST['correo'])         : '';
$departamentoId = isset($_POST['departamento_id'])? intval($_POST['departamento_id']): 0;
$provinciaId    = isset($_POST['provincia_id'])   ? intval($_POST['provincia_id'])   : 0;
$distritoId     = isset($_POST['distrito_id'])    ? intval($_POST['distrito_id'])    : 0;

// 2) Validación de campos obligatorios
$errores = [];

if ($nombre === '') {
    $errores[] = 'El nombre es obligatorio.';
}
if ($ruc === '') {
    $errores[] = 'El RUC es obligatorio.';
} elseif (!preg_match('/^\d{11}$/', $ruc)) {
    $errores[] = 'El RUC debe tener 11 dígitos.';
}
if ($departamentoId <= 0) {
    $errores[] = 'Selecciona un departamento.';
}
if ($provinciaId <= 0) {
    $errores[] = 'Selecciona una provincia.';
}
if ($distritoId <= 0) {
    $errores[] = 'Selecciona un distrito.';
}

// Si hay errores, redirigimos y mostramos mensajes
if (!empty($errores)) {
    $_SESSION['error']   = implode(' ', $errores);
    // Guardamos valores en sesión para volver a cargarlos
    $_SESSION['old'] = [
        'nombre'          => $nombre,
        'ruc'             => $ruc,
        'direccion'       => $direccion,
        'telefono'        => $telefono,
        'correo'          => $correo,
        'departamento_id' => $departamentoId,
        'provincia_id'    => $provinciaId,
        'distrito_id'     => $distritoId
    ];
    header('Location: crear_clientes.php');
    exit;
}

// 3) Insertar registro
$sql = "
  INSERT INTO clientes
    (nombre, ruc, direccion, telefono, correo,
     departamento_id, provincia_id, distrito_id, estado)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Activo')
";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param(
    $stmt,
    'ssssssii',
    $nombre, $ruc, $direccion, $telefono, $correo,
    $departamentoId, $provinciaId, $distritoId
);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['mensaje'] = 'Cliente registrado correctamente.';
    // redirige al listado
    header('Location: clientes.php');
    } 
else {
    $_SESSION['error'] = 'Error al guardar el cliente. Intenta de nuevo.';
    // si falla, vuelve al formulario
    header('Location: crear_clientes.php');
}

mysqli_stmt_close($stmt);
$conn->close();

// 4) Redirigir de nuevo al formulario o al listado
header('Location: crear_clientes.php');
exit;
