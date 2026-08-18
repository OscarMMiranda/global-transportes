<?php
// archivo: /modulos/papeletas/acciones/lista_infracciones.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

// evitar warnings que rompan JSON
ini_set('display_errors', 0);

/*
   TABLA REAL: infracciones
   CAMPOS:
   id, codigo, descripcion, gravedad, porcentaje_uit,
   puntos, monto_base, entidad_emisora_id, norma_legal,
   estado, fecha_creacion, creado_por, modificado_por,
   fecha_modificacion, ip_origen
*/

$sql = "
    SELECT 
        id,
        descripcion
    FROM infracciones
    WHERE estado = 'Activo'
    ORDER BY descripcion ASC
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
