<?php
require_once '../includes/conexion.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehiculo_id = $_POST['vehiculo_id'];
    $tipo_documento_id = $_POST['tipo_documento_id'];
    $numero_documento = $_POST['numero_documento'];
    $empresa_emisora = $_POST['empresa_emisora'];
    $fecha_emision = $_POST['fecha_emision'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];
    $uploaded_by = $_SESSION['usuario_id'];

    // Manejo de archivos
    $archivo_nombre = $_FILES['archivo']['name'];
    $archivo_tmp = $_FILES['archivo']['tmp_name'];
    $destino = '../uploads/' . $archivo_nombre;

    if (move_uploaded_file($archivo_tmp, $destino)) {
        // Insertar en la base de datos
        $sql = "INSERT INTO documentos_vehiculo (vehiculo_id, tipo_documento_id, numero_documento, empresa_emisora, fecha_emision, fecha_vencimiento, archivo, uploaded_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisssssi", $vehiculo_id, $tipo_documento_id, $numero_documento, $empresa_emisora, $fecha_emision, $fecha_vencimiento, $archivo_nombre, $uploaded_by);
        
        if ($stmt->execute()) {
            echo "Documento registrado exitosamente.";
            header("Location: documentos.php"); // Redireccionamos después del registro
            exit();
        } else {
            echo "Error al registrar el documento: " . $stmt->error;
        }
    } else {
        echo "Error al subir el archivo.";
    }
}
?>
