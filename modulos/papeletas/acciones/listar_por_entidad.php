<?php
// archivo: /modulos/infracciones/acciones/listar_por_entidad.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

ini_set('display_errors', 0);

$entidad_id = isset($_POST['entidad_id']) ? intval($_POST['entidad_id']) : 0;

if ($entidad_id <= 0) {
    echo "<option value=''>-- Seleccione infracción --</option>";
    exit;
}

$sql = "
    SELECT 
        id,
        descripcion
    FROM infracciones
    WHERE entidad_emisora_id = $entidad_id
      AND estado = 'Activo'
    ORDER BY descripcion ASC
";

$res = mysqli_query($conn, $sql);

$html = "<option value=''>-- Seleccione infracción --</option>";

if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $id = $row['id'];
        $desc = htmlspecialchars($row['descripcion'], ENT_QUOTES, 'UTF-8');

        $html .= "<option value='$id'>$desc</option>";
    }
}

echo $html;
exit;
?>
