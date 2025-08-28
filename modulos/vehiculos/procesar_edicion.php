<?php
require_once '../../includes/conexion.php';
session_start();

// Activar depuración si es necesario
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

$conn = getConnection();


// Verificar que se recibió el ID del vehículo
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
        {
        if (empty($_POST['id']) || !is_numeric($_POST['id'])) 
            {
            die("<p style='color: red;'>❌ ID de vehículo no válido.</p>");
            }
        $vehiculo_id = intval($_POST['id']);

// Validar que todos los campos requeridos estén presentes
    if (empty($_POST['placa']) || empty($_POST['tipo_id']) || empty($_POST['marca_id']) || empty($_POST['modelo']) || empty($_POST['anio']) || empty($_POST['empresa_id']) || empty($_POST['estado_id'])) 
        {
        die("<p style='color: red;'>❌ Todos los campos obligatorios deben estar completos.</p>");
        }

// Convertir texto a mayúsculas excepto Observaciones
    $placa = strtoupper(trim($_POST['placa']));
    $tipo_id = intval($_POST['tipo_id']);
    $marca_id = intval($_POST['marca_id']);
    $estado_id = intval($_POST['estado_id']);
    $configuracion_id = 1; // Puedes ajustar este valor si es necesario
    $modelo = strtoupper(trim($_POST['modelo']));
    $anio = intval($_POST['anio']);
    $empresa_id = intval($_POST['empresa_id']);
    $observaciones = trim($_POST['observaciones']);

// Actualizar el vehículo en la base de datos
    $sql = "UPDATE vehiculos 
    	SET placa = ?, tipo_id = ?, marca_id = ?, estado_id = ?, configuracion_id = ?, modelo = ?, anio = ?, empresa_id = ?, observaciones = ? 
    	WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiississi", $placa, $tipo_id, $marca_id, $estado_id, $configuracion_id, $modelo, $anio, $empresa_id, $observaciones, $vehiculo_id);

    if ($stmt->execute())
        {
        echo "<p style='color: green;'>✅ Vehículo actualizado correctamente.</p>";
        header("Refresh:1; url=vehiculos.php");
        exit();
        } 
    else {
        echo "<p style='color: red;'>❌ Error al actualizar el vehículo: " . $stmt->error . "</p>";
        }
    
    }


?>
