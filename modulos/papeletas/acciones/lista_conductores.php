<?php
// archivo: /modulos/papeletas/acciones/lista_conductores.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

$sql = "
    SELECT 
        id,
        CONCAT(nombres, ' ', apellidos) AS nombre
    FROM conductores
    WHERE activo = 1
    ORDER BY apellidos ASC, nombres ASC
";

$res = mysqli_query($conn, $sql);

$data = array();

if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
}

echo json_encode($data);
exit;
?>
