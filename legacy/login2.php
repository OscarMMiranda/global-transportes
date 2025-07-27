<?php
session_start();

include('../includes/conexion.php');
include('../includes/funciones.php'); // si lo usás


// Verificamos si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Buscar usuario en la base
    $sql    = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt   = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $fila = $resultado->fetch_assoc();
        
        // Verificar contraseña con hash
        if (password_verify($contrasena, $fila['contrasena'])) {
            $_SESSION['usuario'] = $fila['usuario'];
            $_SESSION['rol'] = $fila['rol'];
            header("Location: dashboard.php"); // Redirige al sistema
            exit;
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}


$fila = mysqli_fetch_assoc($resultado);
$_SESSION['usuario'] = $fila['usuario'];
$_SESSION['rol'] = $fila['rol'];



?>


