<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Registrar errores en un archivo log
ini_set("log_errors", 1);
ini_set("error_log", "debug_log.txt");

// Conexión a la base de datos
$host = "localhost"; // Cambia por tu host si es diferente
$user = "root";      // Usuario de la base de datos
$pass = "";          // Contraseña (coloca la correcta según tu sistema)
$dbname = "tu_base_de_datos";

$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
} else {
    echo "✅ Conexión exitosa.<br>";
}

// Consulta de prueba
$sqlTest = "SELECT COUNT(*) AS total FROM ordenes_trabajo";
$resultTest = $conn->query($sqlTest);

if ($resultTest) {
    $data = $resultTest->fetch_assoc();
    echo "📊 Registros en `ordenes_trabajo`: " . $data['total'];
} else {
    echo "❌ Error en consulta: " . $conn->error;
}
?>
