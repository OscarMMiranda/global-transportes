<?php
require_once '../../includes/conexion.php';
session_start();

// Activar depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// Verificar sesión
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') 
    {
    die("❌ Acceso denegado.");
    }

// Validar método de solicitud
if ($_SERVER["REQUEST_METHOD"] !== "POST") 
    {
    die("❌ Método no permitido.");
    }

// Validar ID del vehículo
if (empty($_POST['id']) || !is_numeric($_POST['id'])) 
    {
    die("❌ ID de vehículo no válido.");
    }

$vehiculo_id = intval($_POST['id']);

// Verificar si el vehículo existe en la base de datos
$sql_verificar = "SELECT placa FROM vehiculos WHERE id = ?";
$stmt_verificar = $conn->prepare($sql_verificar);
$stmt_verificar->bind_param("i", $vehiculo_id);
$stmt_verificar->execute();
$result = $stmt_verificar->get_result();

if ($result->num_rows === 0) 
    {
    die("❌ El vehículo no existe en la base de datos.");
    }

$vehiculo = $result->fetch_assoc();
$placa = $vehiculo['placa'];

// Intentar eliminar el vehículo
$sql_eliminar = "DELETE FROM vehiculos WHERE id = ?";
$stmt_eliminar = $conn->prepare($sql_eliminar);
$stmt_eliminar->bind_param("i", $vehiculo_id);



//  if (!$stmt_eliminar->execute()) 
//    {
//    die("❌ Error al eliminar el vehículo: " . $stmt_eliminar->error);
//    }
//  echo "✅ Vehículo eliminado correctamente.<br>";

if ($stmt_eliminar->execute())
    {
    echo "<p style='color: green;'>✅ Vehículo eliminado correctamente.</p>";
    header("Refresh:2; url=vehiculos.php");
    exit();
    } 
else 
    {
    echo "<p style='color: red;'>❌ Error al eliminar el vehículo: " . $stmt->error . "</p>";
    }



exit();




?>



