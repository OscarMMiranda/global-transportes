<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si PHP funciona en esta carpeta
echo "PHP está funcionando.<br>";

// Intentar incluir conexión
require_once '../../includes/conexion.php';
echo "Conexión incluida exitosamente.<br>";

// Probar consulta a la BD
$resultado = $conn->query("SELECT id, nombre FROM departamentos LIMIT 1");
if ($resultado) {
    echo "Consulta ejecutada. Departamento: " . $resultado->fetch_assoc()['nombre'];
} else {
    echo "Error en consulta: " . $conn->error;
}
