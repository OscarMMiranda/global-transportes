<?php
// archivo: /modulos/papeletas/acciones/lista_vehiculos.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

/*
   TABLA REAL: vehiculos
   CAMPOS:
   id, placa, marca, modelo, activo, ...
*/

$sql = "
    SELECT 
        id,
        placa
    FROM vehiculos
    WHERE activo = 1
    ORDER BY placa ASC
";

$res = mysqli_query($conn, $sql);

$data = array();

if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);
exit;
?>
