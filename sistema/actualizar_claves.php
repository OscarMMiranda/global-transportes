<?php
require_once '../includes/conexion.php';

$usuarios = [
    'admin' => '1234',
    'OMMZ' => 'Samantha2304'
];

foreach ($usuarios as $usuario => $clave) {
    $hash = password_hash($clave, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("UPDATE usuarios SET contrasena = ? WHERE usuario = ?");
    if ($stmt) {
        $stmt->bind_param("ss", $hash, $usuario);
        if ($stmt->execute()) {
            echo "Contraseña actualizada para el usuario: $usuario<br>";
        } else {
            echo "Error al actualizar para $usuario: " . $stmt->error . "<br>";
        }
        $stmt->close();
    } else {
        echo "Error en prepare(): " . $conn->error . "<br>";
    }
}

$conn->close();
?>
