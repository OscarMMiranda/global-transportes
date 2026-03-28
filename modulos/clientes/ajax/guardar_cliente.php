<?php
// archivo: /modulos/clientes/ajax/guardar_cliente.php
header('Content-Type: application/json');

// ===============================
// PHP 5.6: evitar notices que rompen JSON
// ===============================
ini_set('display_errors', 0);
error_reporting(0);

// ===============================
// ENTORNO CORPORATIVO
// ===============================
require_once __DIR__ . '/../../../includes/config.php';

$conn = getConnection();
if (!$conn) {
    echo json_encode([
        "status" => "error",
        "msg"    => "Error de conexión a la base de datos"
    ]);
    exit;
}

// ===============================
// VALIDAR MÉTODO
// ===============================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "status" => "error",
        "msg"    => "Método no permitido"
    ]);
    exit;
}

// ===============================
// CAPTURAR DATOS (SEGURO PARA PHP 5.6)
// ===============================
$id              = isset($_POST['cliente_id']) ? intval($_POST['cliente_id']) : 0;
$nombre          = isset($_POST['nombre']) ? trim($_POST['nombre']) : "";
$tipo_cliente    = isset($_POST['tipo_cliente']) ? trim($_POST['tipo_cliente']) : "";
$departamento_id = isset($_POST['departamento_id']) ? intval($_POST['departamento_id']) : 0;
$provincia_id    = isset($_POST['provincia_id']) ? intval($_POST['provincia_id']) : 0;
$distrito_id     = isset($_POST['distrito_id']) ? intval($_POST['distrito_id']) : 0;
$direccion       = isset($_POST['direccion']) ? trim($_POST['direccion']) : "";
$ruc             = isset($_POST['ruc']) ? trim($_POST['ruc']) : "";
$telefono        = isset($_POST['telefono']) ? trim($_POST['telefono']) : "";
$correo          = isset($_POST['correo']) ? trim($_POST['correo']) : "";

// Auditoría corporativa
$usuario_id 	= isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : null;
$ip_origen  	= isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;

// ===============================
// VALIDACIONES BÁSICAS
// ===============================
if ($nombre === "") {
    echo json_encode(["status" => "error", "msg" => "El nombre es obligatorio"]);
    exit;
}

if ($tipo_cliente === "") {
    echo json_encode(["status" => "error", "msg" => "Debe seleccionar un tipo de cliente"]);
    exit;
}

if ($departamento_id <= 0 || $provincia_id <= 0 || $distrito_id <= 0) {
    echo json_encode(["status" => "error", "msg" => "Debe seleccionar un Ubigeo completo"]);
    exit;
}

// ===============================
// NUEVO CLIENTE
// ===============================
if ($id === 0) {

    $sql = 
		"INSERT INTO clientes 
        	(nombre, tipo_cliente, ruc, direccion, telefono, correo,
        	departamento_id, provincia_id, distrito_id, estado,
        	creado_por, ip_origen)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Activo', ?, ?)
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["status" => "error", "msg" => "Error preparando consulta"]);
        exit;
    }

     $stmt->bind_param(
        "ssssssiiiss",
        $nombre,
        $tipo_cliente,
        $ruc,
        $direccion,
        $telefono,
        $correo,
        $departamento_id,
        $provincia_id,
        $distrito_id,
        $usuario_id,
        $ip_origen
    );


    if ($stmt->execute()) {
        echo json_encode(["status" => "ok", "msg" => "Cliente registrado correctamente"]);
    } else {
        echo json_encode(["status" => "error", "msg" => "Error al registrar cliente"]);
    }

    $stmt->close();
    exit;
}

// ===============================
// EDITAR CLIENTE
// ===============================
$sql = 
	"UPDATE clientes SET
    	nombre = ?,         	/*	(1)		*/
    	tipo_cliente = ?,    	/*	(2)		*/
    	ruc = ?,             	/*	(3)		*/	
    	direccion = ?,       	/*	(4)		*/
    	telefono = ?,        	/*	(5)		*/
    	correo = ?,          	/*	(6)		*/
    	departamento_id = ?, 	/*	(7)		*/
    	provincia_id = ?,    	/*	(8)		*/
    	distrito_id = ?,     	/*	(9)		*/
    	modificado_por = ?   	/*	(10)	*/
		WHERE id = ?            /*	(11)	*/

";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["status" => "error", "msg" => "Error preparando consulta"]);
    exit;
}

$stmt->bind_param(
    "ssssssiii ii",
    $nombre,
    $tipo_cliente,
    $ruc,
    $direccion,
    $telefono,
    $correo,
    $departamento_id,
    $provincia_id,
    $distrito_id,
    $usuario_id,
    $id
);


if ($stmt->execute()) {
    echo json_encode(["status" => "ok", "msg" => "Cliente actualizado correctamente"]);
} else {
    echo json_encode(["status" => "error", "msg" => "Error al actualizar cliente"]);
}

$stmt->close();
exit;
