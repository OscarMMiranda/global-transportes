<?php
// archivo: /modulos/usuarios/acciones/cambiar_password.php
// Descripción: Cambia la contraseña de un usuario.     



require_once '../../../includes/config.php';
$conn = getConnection();

$id = $_POST['id'];
$pass = $_POST['password'];

if (!$id || !$pass) {
    echo json_encode(['ok' => false, 'msg' => 'Datos incompletos']);
    exit;
}

$hash = password_hash($pass, PASSWORD_BCRYPT);

$sql = "UPDATE usuarios SET contrasena = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $hash, $id);

echo json_encode([
    'ok' => $stmt->execute(),
    'msg' => $stmt->execute() ? 'Contraseña actualizada' : 'Error al actualizar'
]);

