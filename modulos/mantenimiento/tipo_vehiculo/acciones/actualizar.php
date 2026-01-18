<?php
// archivo: /modulos/mantenimiento/tipo_vehiculo/acciones/actualizar.php
header('Content-Type: application/json');

require_once __DIR__ . '/../../../../includes/conexion.php';
require_once __DIR__ . '/../../../../includes/permisos.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

requirePermiso("tipo_vehiculo", "editar");

if (!isset($_POST['id']) || (int)$_POST['id'] <= 0) {
    echo json_encode(array("ok" => false, "msg" => "ID inválido."));
    exit;
}

if (!isset($_POST['nombre']) || trim($_POST['nombre']) === "") {
    echo json_encode(array("ok" => false, "msg" => "El nombre es obligatorio."));
    exit;
}

if (!isset($_POST['categoria_id']) || (int)$_POST['categoria_id'] <= 0) {
    echo json_encode(array("ok" => false, "msg" => "Debe seleccionar una categoría."));
    exit;
}

$id = (int)$_POST['id'];
$nombre = trim($_POST['nombre']);
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : "";
$categoria_id = (int)$_POST['categoria_id'];

$conn = getConnection();

// validar categoría
$sqlCat = "SELECT COUNT(*) FROM categoria_vehiculo WHERE id = ?";
$stmtCat = $conn->prepare($sqlCat);
$stmtCat->bind_param("i", $categoria_id);
$stmtCat->execute();
$stmtCat->bind_result($existeCat);
$stmtCat->fetch();
$stmtCat->close();

if ($existeCat == 0) {
    echo json_encode(array("ok" => false, "msg" => "La categoría seleccionada no existe."));
    exit;
}

// validar duplicado por nombre (excluyendo el mismo id)
$sqlDup = "SELECT COUNT(*) FROM tipo_vehiculo WHERE nombre = ? AND id <> ? AND eliminado = 0";
$stmtDup = $conn->prepare($sqlDup);
$stmtDup->bind_param("si", $nombre, $id);
$stmtDup->execute();
$stmtDup->bind_result($existe);
$stmtDup->fetch();
$stmtDup->close();

if ($existe > 0) {
    echo json_encode(array("ok" => false, "msg" => "Ya existe otro tipo de vehículo con ese nombre."));
    exit;
}

$usuario_id = isset($_SESSION["usuario_id"]) ? (int)$_SESSION["usuario_id"] : 0;

$sql = "
    UPDATE tipo_vehiculo
    SET nombre = ?, descripcion = ?, categoria_id = ?, fecha_modificacion = NOW()
    WHERE id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $nombre, $descripcion, $categoria_id, $id);

if ($stmt->execute()) {

    // historial
    $sqlHist = "
        INSERT INTO tipo_vehiculo_historial (tipo_id, usuario_id, cambio, fecha)
        VALUES (?, ?, ?, NOW())
    ";
    $stmtHist = $conn->prepare($sqlHist);
    $cambio = "Actualización de tipo de vehículo";
    $stmtHist->bind_param("iis", $id, $usuario_id, $cambio);
    $stmtHist->execute();
    $stmtHist->close();

    echo json_encode(array("ok" => true, "msg" => "Tipo de vehículo actualizado correctamente."));
} else {
    echo json_encode(array("ok" => false, "msg" => "Error al actualizar en la base de datos."));
}

$stmt->close();
exit;