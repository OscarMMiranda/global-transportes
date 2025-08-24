<?php

// 1) Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors',     1);
ini_set('error_log',      __DIR__ . '/error_log.txt');

// 2) Cargar configuración y conexión
require_once __DIR__ . '/../../includes/config.php';
$conn = getConnection();



// Mostrar errores en desarrollo (No recomendado en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar y sanitizar datos del formulario
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $dni = trim($_POST['dni']);
    $licencia = trim($_POST['licencia_conducir']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);

    // Validar que los campos requeridos no estén vacíos
    if (empty($nombres) || empty($apellidos) || empty($dni) || empty($licencia)) {
        die("❌ Error: Todos los campos requeridos deben estar llenos.");
    }

    // Validar que el DNI tenga exactamente 8 dígitos numéricos
    if (!preg_match('/^\d{8}$/', $dni)) {
        die("❌ Error: El DNI debe contener exactamente 8 dígitos numéricos.");
    }

    // Validar formato del correo electrónico (si fue proporcionado)
    if (!empty($correo) && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        die("❌ Error: El correo electrónico no es válido.");
    }

    // Definir la consulta SQL con el DNI incluido
    $sql = "INSERT INTO conductores (dni, nombres, apellidos, licencia_conducir, telefono, correo, activo) 
            VALUES (?, ?, ?, ?, ?, ?, 1)";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("❌ Error preparando la consulta: " . $conn->error);
    }

    // Vincular los parámetros (ahora incluye DNI y datos sanitizados)
    $stmt->bind_param("ssssss", $dni, $nombres, $apellidos, $licencia, $telefono, $correo);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir al usuario con un mensaje de éxito
        header("Location: conductores.php?mensaje=Conductor registrado exitosamente");
        exit();
    } else {
        die("❌ Error al registrar el conductor: " . $stmt->error);
    }

    // Cerrar la consulta
    $stmt->close();
}
?>
