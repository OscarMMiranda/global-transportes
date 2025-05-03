<?php
include '../includes/conexion.php';

// Reemplaza con el ID del usuario que quieras actualizar
$usuario_id = 1;

// Nueva contraseña en texto plano
$nueva_clave = "123456";

// Generar hash
$hash = password_hash($nueva_clave, PASSWORD_DEFAULT);

// Actualizar en la base de datos
$sql = "UPDATE usuarios SET contrasena = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $hash, $usuario_id);

if ($stmt->execute()) {
    echo "Contraseña actualizada correctamente para el usuario ID $usuario_id.";
    echo "<br>Nuevo hash: $hash";
} else {
    echo "Error al actualizar la contraseña: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
