<?php
// archivo: /modulos/papeletas/acciones/lista_entidades.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

/*
   TABLA REAL: entidad_emisora
   CAMPOS: id, nombre
*/

$sql = "
    SELECT 
        id,
        nombre
    FROM entidad_emisora
    ORDER BY nombre ASC
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
