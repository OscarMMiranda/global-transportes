<?php
// ✅ Activar la depuración de errores en pantalla y en archivo
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt'); // Guarda los errores en un archivo

// ✅ Forzar un error de prueba para verificar `error_log.txt`
trigger_error("⚠️ Error de prueba para verificar el log", E_USER_WARNING);

try {
    // ✅ Intenta conectar con la base de datos
    $conn = new mysqli("localhost", "admin", "1234", "sistema");

    // ✅ Verifica si la conexión falló
    if ($conn->connect_error) {
        throw new Exception("❌ Error de conexión: " . $conn->connect_error);
    } else {
        echo "✅ Conexión a MySQL exitosa.";
    }
} catch (Exception $e) {
    error_log($e->getMessage()); // Registra el error en el log sin mostrarlo
    echo "⚠️ Hubo un problema, revisa `error_log.txt` para más detalles.";
}
?>
