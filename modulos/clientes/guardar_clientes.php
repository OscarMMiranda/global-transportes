<?php
// Mostrar errores para depuración (quitar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
require_once '../../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar y limpiar datos
    $nombre           = mysqli_real_escape_string($conn, $_POST['nombre']);
    $ruc              = mysqli_real_escape_string($conn, $_POST['ruc']);
    $direccion        = mysqli_real_escape_string($conn, $_POST['direccion']);
    $telefono         = mysqli_real_escape_string($conn, $_POST['telefono']);
    $correo           = mysqli_real_escape_string($conn, $_POST['correo']);
    $departamento_id  = isset($_POST['departamento_id']) ? (int) $_POST['departamento_id'] : null;
    $provincia_id     = isset($_POST['provincia_id']) ? (int) $_POST['provincia_id'] : null;
    $distrito_id      = isset($_POST['distrito_id']) ? (int) $_POST['distrito_id'] : null;

    // Validación básica
    if (empty($nombre) || empty($ruc) || empty($direccion) || empty($telefono) || empty($correo)) {
        echo "Por favor completa todos los campos obligatorios.";
        exit;
    }

    // Consulta SQL
    $query = "INSERT INTO clientes (
                nombre, ruc, direccion, telefono, correo,
                departamento_id, provincia_id, distrito_id, estado
              ) VALUES (
                '$nombre', '$ruc', '$direccion', '$telefono', '$correo',
                $departamento_id, $provincia_id, $distrito_id, 'Activo'
              )";

    if (mysqli_query($conn, $query)) {
        header("Location: listar_clientes.php?mensaje=Cliente registrado correctamente");
        exit;
    } else {
        echo "Error al guardar el cliente: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    echo "Acceso denegado.";
}
?>
