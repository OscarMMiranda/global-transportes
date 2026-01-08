<?php
// archivo: /modulos/usuarios/acciones/ver.php
// Devuelve los detalles de un usuario con el nombre del rol

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';

$conn = getConnection();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

header('Content-Type: application/json; charset=utf-8');

if ($id <= 0) {
    echo json_encode(["error" => "ID inválido"]);
    exit;
}

$sql = "SELECT 
            u.id,
            u.nombre,
            u.apellido,
            u.usuario,
            u.correo,
            r.nombre AS rol,
            u.eliminado,
            u.creado_en
        FROM usuarios u
        LEFT JOIN roles r ON r.id = u.rol
        WHERE u.id = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

echo json_encode($usuario);