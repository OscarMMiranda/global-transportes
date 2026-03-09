<?php
// /modulos/clientes/controllers/FormController.php

if (!defined('GT_APP')) {
    define('GT_APP', true);
}

// =====================================
// 1) Inicializar variables
// =====================================
$editing  = false;
$cliente  = array(
    'id'        => '',
    'nombre'    => '',
    'ruc'       => '',
    'direccion' => '',
    'correo'    => '',
    'telefono'  => ''
);

$errorMessage = '';

// =====================================
// 2) Si viene ID → modo edición
// =====================================
if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
    $editing = true;
    $id = (int) $_GET['id'];

    $sql = "SELECT id, nombre, ruc, direccion, correo, telefono
            FROM clientes
            WHERE id = " . $id . " LIMIT 1";

    $res = mysqli_query($conn, $sql);

    if ($res && mysqli_num_rows($res) === 1) {
        $cliente = mysqli_fetch_assoc($res);
    } else {
        $errorMessage = "Cliente no encontrado.";
    }
}

// =====================================
// 3) Procesar POST (guardar)
// =====================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id        = isset($_POST['id']) ? (int) $_POST['id'] : null;
    $nombre    = trim($_POST['nombre']);
    $ruc       = trim($_POST['ruc']);
    $direccion = trim($_POST['direccion']);
    $correo    = trim($_POST['correo']);
    $telefono  = trim($_POST['telefono']);

    // Validaciones mínimas
    if ($nombre === '') {
        $errorMessage = "El nombre es obligatorio.";
    }

    if ($errorMessage === '') {

        if ($id) {
            // UPDATE
            $sql = sprintf(
                "UPDATE clientes SET 
                    nombre='%s',
                    ruc='%s',
                    direccion='%s',
                    correo='%s',
                    telefono='%s'
                 WHERE id=%d",
                mysqli_real_escape_string($conn, $nombre),
                mysqli_real_escape_string($conn, $ruc),
                mysqli_real_escape_string($conn, $direccion),
                mysqli_real_escape_string($conn, $correo),
                mysqli_real_escape_string($conn, $telefono),
                $id
            );
        } else {
            // INSERT
            $sql = sprintf(
                "INSERT INTO clientes (nombre, ruc, direccion, correo, telefono)
                 VALUES ('%s','%s','%s','%s','%s')",
                mysqli_real_escape_string($conn, $nombre),
                mysqli_real_escape_string($conn, $ruc),
                mysqli_real_escape_string($conn, $direccion),
                mysqli_real_escape_string($conn, $correo),
                mysqli_real_escape_string($conn, $telefono)
            );
        }

        if (mysqli_query($conn, $sql)) {
            header("Location: index.php?action=list&msg=ok");
            exit;
        } else {
            $errorMessage = "Error al guardar: " . mysqli_error($conn);
        }
    }

    // Mantener datos en el formulario si hubo error
    $cliente = array(
        'id'        => $id,
        'nombre'    => $nombre,
        'ruc'       => $ruc,
        'direccion' => $direccion,
        'correo'    => $correo,
        'telefono'  => $telefono
    );
}

// =====================================
// 4) Cargar vista
// =====================================
require __DIR__ . '/../views/form.php';
