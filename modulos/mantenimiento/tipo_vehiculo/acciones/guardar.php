<?php
// archivo: /modulos/mantenimiento/tipo_vehiculo/acciones/guardar.php
header('Content-Type: application/json');

// Cargar configuración global (rutas, conexión, funciones, etc.)
require_once __DIR__ . '/../../../../includes/config.php';
// Cargar sistema de permisos usando la ruta ya definida
require_once INCLUDES_PATH . '/permisos.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

requirePermiso("tipo_vehiculo", "crear");

// Validaciones básicas
if (!isset($_POST['nombre']) || trim($_POST['nombre']) === "") {
    echo json_encode(array("ok" => false, "msg" => "El nombre es obligatorio."));
    exit;
}

if (!isset($_POST['categoria_id']) || (int)$_POST['categoria_id'] <= 0) {
    echo json_encode(array("ok" => false, "msg" => "Debe seleccionar una categoría."));
    exit;
}

$nombre       = trim($_POST['nombre']);
$descripcion  = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : "";
$categoria_id = (int)$_POST['categoria_id'];

$conn = getConnection();

if (!$conn) {
    echo json_encode(array("ok" => false, "msg" => "Error de conexión a la base de datos."));
    exit;
}

// Validar categoría existente
$sqlCat  = "SELECT COUNT(*) FROM categoria_vehiculo WHERE id = ?";
$stmtCat = $conn->prepare($sqlCat);

if (!$stmtCat) {
    echo json_encode(array("ok" => false, "msg" => "Error al preparar validación de categoría."));
    exit;
}

$stmtCat->bind_param("i", $categoria_id);
$stmtCat->execute();
$stmtCat->bind_result($existeCat);
$stmtCat->fetch();
$stmtCat->close();

if ((int)$existeCat === 0) {
    echo json_encode(array("ok" => false, "msg" => "La categoría seleccionada no existe."));
    exit;
}

// Validar duplicado por nombre
$sqlDup  = "SELECT COUNT(*) FROM tipo_vehiculo WHERE nombre = ? AND eliminado = 0";
$stmtDup = $conn->prepare($sqlDup);

if (!$stmtDup) {
    echo json_encode(array("ok" => false, "msg" => "Error al preparar validación de duplicado."));
    exit;
}

$stmtDup->bind_param("s", $nombre);
$stmtDup->execute();
$stmtDup->bind_result($existe);
$stmtDup->fetch();
$stmtDup->close();

if ((int)$existe > 0) {
    echo json_encode(array("ok" => false, "msg" => "Ya existe un tipo de vehículo con ese nombre."));
    exit;
}

$creado_por = isset($_SESSION["usuario_id"]) ? (int)$_SESSION["usuario_id"] : 0;

// Insertar registro
$sql = "
    INSERT INTO tipo_vehiculo 
    (nombre, descripcion, categoria_id, fecha_creado, creado_por, eliminado)
    VALUES (?, ?, ?, NOW(), ?, 0)
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(array("ok" => false, "msg" => "Error al preparar la inserción."));
    exit;
}

$stmt->bind_param("ssii", $nombre, $descripcion, $categoria_id, $creado_por);

if ($stmt->execute()) {

    $nuevoId = $stmt->insert_id;

    // Registrar historial si hay usuario
    if ($creado_por > 0) {
        $sqlHist = "
            INSERT INTO tipo_vehiculo_historial (tipo_id, usuario_id, cambio, fecha)
            VALUES (?, ?, ?, NOW())
        ";
        $stmtHist = $conn->prepare($sqlHist);

        if ($stmtHist) {
            $cambio = "Creación de tipo de vehículo";
            $stmtHist->bind_param("iis", $nuevoId, $creado_por, $cambio);
            $stmtHist->execute();
            $stmtHist->close();
        }
    }

    echo json_encode(array("ok" => true, "msg" => "Tipo de vehículo creado correctamente."));
} else {
    echo json_encode(array("ok" => false, "msg" => "Error al guardar en la base de datos."));
}

$stmt->close();
exit;