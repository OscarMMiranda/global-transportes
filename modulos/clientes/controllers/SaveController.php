<?php
    // archivo: modulos/clientes/controllers/SaveController.php

if (!defined('GT_APP')) {
    define('GT_APP', true);
}

// Solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php?action=list");
    exit;
}

// Sanitizar
$id     = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$ruc    = isset($_POST['ruc']) ? trim($_POST['ruc']) : '';
$dir    = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';
$tel    = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
$mail   = isset($_POST['correo']) ? trim($_POST['correo']) : '';

$depId  = isset($_POST['departamento_id']) ? (int) $_POST['departamento_id'] : 0;
$provId = isset($_POST['provincia_id']) ? (int) $_POST['provincia_id'] : 0;
$distId = isset($_POST['distrito_id']) ? (int) $_POST['distrito_id'] : 0;

// Validaciones
if ($nombre === '' || $depId === 0 || $provId === 0 || $distId === 0) {
    header("Location: index.php?action=form&id={$id}&msg=error");
    exit;
}

// Verificar duplicado de RUC
$sqlChk = "SELECT id FROM clientes WHERE ruc = ? AND id <> ? LIMIT 1";
$stmtChk = mysqli_prepare($conn, $sqlChk);
mysqli_stmt_bind_param($stmtChk, "si", $ruc, $id);
mysqli_stmt_execute($stmtChk);
$resChk = mysqli_stmt_get_result($stmtChk);

if ($resChk && mysqli_num_rows($resChk) > 0) {
    header("Location: index.php?action=form&id={$id}&msg=duplicado");
    exit;
}
mysqli_stmt_close($stmtChk);

// Guardar
if ($id > 0) {
    // UPDATE
    $sql = "
        UPDATE clientes SET
            nombre=?, ruc=?, direccion=?, telefono=?, correo=?,
            departamento_id=?, provincia_id=?, distrito_id=?
        WHERE id=?
    ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssi",
        $nombre, $ruc, $dir, $tel, $mail,
        $depId, $provId, $distId,
        $id
    );
} else {
    // INSERT
    $sql = "
        INSERT INTO clientes
            (nombre, ruc, direccion, telefono, correo,
             departamento_id, provincia_id, distrito_id, estado)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Activo')
    ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "ssssssss",
        $nombre, $ruc, $dir, $tel, $mail,
        $depId, $provId, $distId
    );
}

if (!mysqli_stmt_execute($stmt)) {
    echo "Error SQL: " . mysqli_error($conn);
    exit;
}

mysqli_stmt_close($stmt);

// OK
header("Location: index.php?action=list&msg=ok");
exit;
