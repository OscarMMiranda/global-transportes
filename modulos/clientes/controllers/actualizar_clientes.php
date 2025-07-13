<?php

	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);

   require_once rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/includes/config.php';

// 2) Solo procesamos POST. Si alguien accede por GET, redirigimos
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: clientes.php');
    exit;
}

$ruc       = isset($_POST['ruc'])       ? trim($_POST['ruc'])       : '';
$direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';
$telefono  = isset($_POST['telefono'])  ? trim($_POST['telefono'])  : '';
$correo    = isset($_POST['correo'])    ? trim($_POST['correo'])    : '';
$dpto_id    = filter_input(
		INPUT_POST, 'departamento_id', 
		FILTER_VALIDATE_INT,
		['options' => ['default' => 0]]);
$prov_id    = filter_input(
		INPUT_POST, 'provincia_id', 
		FILTER_VALIDATE_INT,
		['options' => ['default' => 0]]);
$dist_id    = filter_input(
		INPUT_POST, 'distrito_id', 
		FILTER_VALIDATE_INT,
		['options' => ['default' => 0]]);

// Validaciones básicas
if (!$id || $nombre === '') {
    header("Location: editar_clientes.php?id=$id&msg=error");
    exit;
}

$sql = "
    UPDATE clientes
    SET nombre           = ?,
        ruc              = ?,
        direccion        = ?,
        telefono         = ?,
        correo           = ?,
        departamento_id  = ?,
        provincia_id     = ?,
        distrito_id      = ?
    WHERE id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssssiiii",
    $nombre,
    $ruc,
    $direccion,
    $telefono,
    $correo,
    $dpto_id,
    $prov_id,
    $dist_id,
    $id
);

// if ($stmt->execute()) {
//     header("Location: clientes.php?id=$id&msg=ok");
// } else {
//     // Para debug, imprime el error real
//     echo "Error en UPDATE: " . $stmt->error;
//     // Luego redirige o gestiona el fallo
//     // header("Location: editar_cliente.php?id=$id&msg=error");
// }
// exit;


// REDIRECCIÓN AL LISTADO
if ($ok) {
    header('Location: /modulos/clientes/clientes.php?msg=ok');
} else {
    header('Location: /modulos/clientes/clientes.php?msg=error');
}
exit;
