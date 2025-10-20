<?php
    //  archivo :   /modulos/ubicaciones/ajax_departamento.php
    

require_once('../../includes/conexion.php');

$sql = "SELECT id, nombre FROM departamentos ORDER BY nombre ASC";
$resultado = mysqli_query($conexion, $sql);

echo '<option value="">Seleccione un departamento</option>';
while ($row = mysqli_fetch_assoc($resultado)) {
    echo '<option value="' . $row['id'] . '">' . $row['nombre'] . '</option>';
}
?>
