<?php
// get_provincias.php
require_once __DIR__ . '/../../includes/conexion.php';  // Ajusta la ruta según la ubicación del archivo

if(isset($_POST["departamento_id"])){
  $departamento_id = intval($_POST["departamento_id"]);
  
  // Consulta para obtener provincias filtradas por departamento
  $query = "SELECT id, nombre FROM provincias WHERE departamento_id = ? ORDER BY nombre ASC";
  $stmt = $conn->prepare($query);
  if(!$stmt){
      die("Error en la preparación: " . $conn->error);
  }
  $stmt->bind_param("i", $departamento_id);
  $stmt->execute();
  $result = $stmt->get_result();
  
  $options = '<option value="">Elija una provincia</option>';
  while($row = $result->fetch_assoc()){
    $options .= '<option value="' . $row['id'] . '">' . htmlspecialchars($row['nombre']) . '</option>';
  }
  echo $options;
}
?>
